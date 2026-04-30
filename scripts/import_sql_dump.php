<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require __DIR__ . '/../vendor/autoload.php';

$projectRoot = realpath(__DIR__ . '/..') ?: (__DIR__ . '/..');

if (is_file($projectRoot . '/.env')) {
    Dotenv::createImmutable($projectRoot)->safeLoad();
}

function envString(string $key, ?string $default = null): ?string
{
    $value = $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key);
    if ($value === false || $value === null || $value === '') {
        return $default;
    }
    return (string) $value;
}

function envInt(string $key, int $default): int
{
    $value = envString($key);
    if ($value === null) {
        return $default;
    }
    return (int) $value;
}

function panic(string $message, int $code = 1): void
{
    fwrite(STDERR, $message . PHP_EOL);
    exit($code);
}

$dumpPath = $argv[1] ?? null;
$args = array_slice($argv, 2);

if (!is_string($dumpPath) || $dumpPath === '') {
    panic('Usage: php scripts/import_sql_dump.php <path-to-sql-dump> [--no-backup]');
}

$dumpFullPath = $dumpPath;
if (!str_starts_with($dumpFullPath, '/') && !preg_match('/^[A-Za-z]:\\\\/', $dumpFullPath)) {
    $dumpFullPath = $projectRoot . '/' . ltrim($dumpFullPath, '/');
}

if (!is_file($dumpFullPath)) {
    panic("SQL dump not found: {$dumpFullPath}");
}

$doBackup = !in_array('--no-backup', $args, true);

$dbHost = envString('DB_HOST', '127.0.0.1') ?? '127.0.0.1';
$dbPort = envInt('DB_PORT', 3306);
$dbName = envString('DB_DATABASE') ?? '';
$dbUser = envString('DB_USERNAME') ?? '';
$dbPass = envString('DB_PASSWORD', '') ?? '';

if ($dbName === '' || $dbUser === '') {
    panic('Missing DB_DATABASE / DB_USERNAME in .env');
}

$dsnServer = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $dbHost, $dbPort);
$dsnDb = sprintf('mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4', $dbHost, $dbPort, $dbName);

$pdoServer = new PDO($dsnServer, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

$pdoServer->exec(sprintf('CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci', str_replace('`', '``', $dbName)));

$pdo = new PDO($dsnDb, $dbUser, $dbPass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
]);

function listTables(PDO $pdo, string $dbName): array
{
    $stmt = $pdo->prepare('SELECT table_name FROM information_schema.tables WHERE table_schema = ? AND table_type = "BASE TABLE" ORDER BY table_name');
    $stmt->execute([$dbName]);
    return array_map(static fn (array $row) => (string) $row['table_name'], $stmt->fetchAll());
}

function quoteIdentifier(string $name): string
{
    return '`' . str_replace('`', '``', $name) . '`';
}

function backupDatabase(PDO $pdo, string $dbName): ?string
{
    $tables = listTables($pdo, $dbName);
    if (count($tables) === 0) {
        return null;
    }

    $suffix = date('Ymd_His');
    $backupDb = $dbName . '_backup_' . $suffix;

    $pdo->exec(sprintf('CREATE DATABASE IF NOT EXISTS %s CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci', quoteIdentifier($backupDb)));
    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');

    foreach ($tables as $table) {
        $pdo->exec(sprintf('DROP TABLE IF EXISTS %s.%s', quoteIdentifier($backupDb), quoteIdentifier($table)));
        $pdo->exec(sprintf('CREATE TABLE %s.%s LIKE %s.%s', quoteIdentifier($backupDb), quoteIdentifier($table), quoteIdentifier($dbName), quoteIdentifier($table)));
        $pdo->exec(sprintf('INSERT INTO %s.%s SELECT * FROM %s.%s', quoteIdentifier($backupDb), quoteIdentifier($table), quoteIdentifier($dbName), quoteIdentifier($table)));
    }

    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');

    return $backupDb;
}

function dropAllTables(PDO $pdo, string $dbName): void
{
    $tables = listTables($pdo, $dbName);
    if (count($tables) === 0) {
        return;
    }

    $pdo->exec('SET FOREIGN_KEY_CHECKS=0');

    foreach ($tables as $table) {
        $pdo->exec(sprintf('DROP TABLE IF EXISTS %s', quoteIdentifier($table)));
    }

    $pdo->exec('SET FOREIGN_KEY_CHECKS=1');
}

final class SqlDumpStreamer
{
    private bool $inSingle = false;
    private bool $inDouble = false;
    private bool $inBacktick = false;
    private bool $inLineComment = false;
    private bool $inBlockComment = false;
    private bool $inVersionedComment = false;

    private string $statement = '';
    private string $prevChar = "\n";

    /**
     * @param callable(string):void $onStatement
     */
    public function stream(string $path, callable $onStatement): void
    {
        $handle = fopen($path, 'rb');
        if ($handle === false) {
            throw new RuntimeException("Unable to open file: {$path}");
        }

        while (!feof($handle)) {
            $chunk = fread($handle, 1024 * 1024);
            if ($chunk === false) {
                break;
            }

            $this->consume($chunk, $onStatement);
        }

        fclose($handle);

        $tail = trim($this->statement);
        if ($tail !== '') {
            $onStatement($tail);
        }
    }

    /**
     * @param callable(string):void $onStatement
     */
    private function consume(string $data, callable $onStatement): void
    {
        $len = strlen($data);

        for ($i = 0; $i < $len; $i++) {
            $ch = $data[$i];
            $next = ($i + 1 < $len) ? $data[$i + 1] : '';
            $next2 = ($i + 2 < $len) ? $data[$i + 2] : '';

            if ($this->inLineComment) {
                if ($ch === "\n") {
                    $this->inLineComment = false;
                    $this->prevChar = $ch;
                }
                continue;
            }

            if ($this->inBlockComment) {
                if ($ch === '*' && $next === '/') {
                    $this->inBlockComment = false;
                    $i++;
                    $this->prevChar = '/';
                    continue;
                }
                continue;
            }

            if ($this->inVersionedComment) {
                // Keep content, but strip the closing */
                if ($ch === '*' && $next === '/') {
                    $this->inVersionedComment = false;
                    $i++;
                    $this->prevChar = '/';
                    continue;
                }

                $this->statement .= $ch;
                $this->prevChar = $ch;
                continue;
            }

            if (!$this->inSingle && !$this->inDouble && !$this->inBacktick) {
                // Start of line comment: "# ..."
                if ($ch === '#') {
                    $this->inLineComment = true;
                    continue;
                }

                // Start of line comment: "-- ..." (must be preceded by whitespace/newline and followed by whitespace)
                if ($ch === '-' && $next === '-' && (ctype_space($this->prevChar) || $this->prevChar === "\n") && ($next2 === '' || ctype_space($next2))) {
                    $this->inLineComment = true;
                    $i += 1;
                    continue;
                }

                // Block comment "/* ... */" (skip), but keep versioned comments "/*! ... */"
                if ($ch === '/' && $next === '*') {
                    if ($next2 === '!') {
                        // Versioned comment: strip the "/*!#### " prefix if present.
                        $this->inVersionedComment = true;
                        $i += 2; // skip "/*!"

                        // Eat digits and optional space after them.
                        while ($i + 1 < $len && ctype_digit($data[$i + 1])) {
                            $i++;
                        }
                        if ($i + 1 < $len && $data[$i + 1] === ' ') {
                            $i++;
                        }

                        $this->prevChar = '!';
                        continue;
                    }

                    $this->inBlockComment = true;
                    $i += 1;
                    continue;
                }
            }

            // Toggle string/backtick states (ignore escaped quotes)
            if ($ch === "'" && !$this->inDouble && !$this->inBacktick) {
                if (!$this->isEscaped($data, $i)) {
                    $this->inSingle = !$this->inSingle;
                }
            } elseif ($ch === '"' && !$this->inSingle && !$this->inBacktick) {
                if (!$this->isEscaped($data, $i)) {
                    $this->inDouble = !$this->inDouble;
                }
            } elseif ($ch === '`' && !$this->inSingle && !$this->inDouble) {
                $this->inBacktick = !$this->inBacktick;
            }

            if ($ch === ';' && !$this->inSingle && !$this->inDouble && !$this->inBacktick) {
                $sql = trim($this->statement);
                $this->statement = '';
                $this->prevChar = $ch;

                if ($sql !== '') {
                    $onStatement($sql);
                }

                continue;
            }

            $this->statement .= $ch;
            $this->prevChar = $ch;
        }
    }

    private function isEscaped(string $data, int $pos): bool
    {
        $slashes = 0;
        for ($i = $pos - 1; $i >= 0; $i--) {
            if ($data[$i] !== '\\') {
                break;
            }
            $slashes++;
        }
        return ($slashes % 2) === 1;
    }
}

echo "Target DB: {$dbHost}:{$dbPort} / {$dbName}\n";

if ($doBackup) {
    echo "Creating backup...\n";
    $backupDb = backupDatabase($pdo, $dbName);
    echo $backupDb ? "Backup created: {$backupDb}\n" : "No tables found; skipping backup.\n";
} else {
    echo "Backup skipped (--no-backup)\n";
}

echo "Dropping existing tables...\n";
dropAllTables($pdo, $dbName);
echo "Importing dump: {$dumpFullPath}\n";

$pdo->exec('SET FOREIGN_KEY_CHECKS=0');
$importer = new SqlDumpStreamer();
$count = 0;

$importer->stream($dumpFullPath, function (string $sql) use ($pdo, &$count) {
    $trimmed = ltrim($sql);
    if ($trimmed === '' || str_starts_with($trimmed, '--')) {
        return;
    }

    $pdo->exec($sql);
    $count++;

    if ($count % 200 === 0) {
        echo "Executed {$count} statements...\n";
    }
});

$pdo->exec('SET FOREIGN_KEY_CHECKS=1');

echo "Done. Executed {$count} statements.\n";
