<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ImportSqlDump extends Command
{
    protected $signature = 'db:import-sql
                            {path=demowebjalan_rigel.sql : Path file .sql relatif terhadap project root}
                            {--wipe : Hapus semua tabel sebelum import}
                            {--migrate : Jalankan migrate setelah import}
                            {--seed-accounts : Jalankan AdminUserSeeder + MarketingUserSeeder setelah import}
                            {--force : Izinkan jalan di production}';

    protected $description = 'Import SQL dump ke database yang sedang terkonfigurasi';

    public function handle(): int
    {
        if (app()->environment('production') && ! $this->option('force')) {
            $this->error('Refusing to run in production without --force');
            return self::FAILURE;
        }

        if (config('database.default') === 'sqlite') {
            $this->error('This command is intended for MySQL/MariaDB, not sqlite.');
            return self::FAILURE;
        }

        $argPath = (string) $this->argument('path');
        $path = str_starts_with($argPath, '/')
            ? $argPath
            : base_path($argPath);

        if (! is_file($path)) {
            $this->error("SQL file not found: {$path}");
            return self::FAILURE;
        }

        if ($this->option('wipe')) {
            $this->warn('Wiping all tables...');
            $this->wipeAllTables();
        }

        $this->info("Importing: {$path}");

        $sql = file_get_contents($path);
        if ($sql === false) {
            $this->error('Failed to read SQL file.');
            return self::FAILURE;
        }

        $statements = $this->splitSqlStatements($sql);
        $total = count($statements);

        if ($total === 0) {
            $this->warn('No SQL statements found.');
            return self::SUCCESS;
        }

        $bar = $this->output->createProgressBar($total);
        $bar->start();

        foreach ($statements as $statement) {
            DB::unprepared($statement);
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        if ($this->option('migrate')) {
            $this->call('migrate');
        }

        if ($this->option('seed-accounts')) {
            $this->call('db:seed', ['--class' => \Database\Seeders\AdminUserSeeder::class]);
            $this->call('db:seed', ['--class' => \Database\Seeders\MarketingUserSeeder::class]);
        }

        $this->info('Import completed.');

        return self::SUCCESS;
    }

    private function wipeAllTables(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        $database = (string) config('database.connections.'.config('database.default').'.database');
        $tables = DB::select('SHOW FULL TABLES WHERE Table_type = "BASE TABLE"');

        foreach ($tables as $row) {
            $tableName = null;
            foreach ((array) $row as $key => $value) {
                if (stripos((string) $key, 'Tables_in_') === 0 || $key === "Tables_in_{$database}") {
                    $tableName = (string) $value;
                    break;
                }
            }

            if (! $tableName) {
                $values = array_values((array) $row);
                $tableName = (string) ($values[0] ?? '');
            }

            if ($tableName !== '') {
                DB::statement('DROP TABLE IF EXISTS `'.$tableName.'`');
            }
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Split SQL dump into executable statements (basic parser, good enough for phpMyAdmin dumps).
     *
     * @return array<int, string>
     */
    private function splitSqlStatements(string $sql): array
    {
        $sql = str_replace("\r\n", "\n", $sql);
        $sql = str_replace("\r", "\n", $sql);

        // Remove block comments (including /*!40101 ... */)
        $sql = preg_replace('~/\\*.*?\\*/~s', '', $sql) ?? $sql;

        // Remove line comments
        $lines = explode("\n", $sql);
        $filtered = [];
        foreach ($lines as $line) {
            $trim = ltrim($line);
            if ($trim === '' || str_starts_with($trim, '--') || str_starts_with($trim, '#')) {
                continue;
            }
            $filtered[] = $line;
        }
        $sql = implode("\n", $filtered);

        $statements = [];
        $buffer = '';
        $inSingle = false;
        $inDouble = false;
        $inBacktick = false;
        $len = strlen($sql);

        for ($i = 0; $i < $len; $i++) {
            $ch = $sql[$i];

            if ($ch === "'" && ! $inDouble && ! $inBacktick) {
                $escaped = $i > 0 && $sql[$i - 1] === '\\';
                if (! $escaped) {
                    $inSingle = ! $inSingle;
                }
                $buffer .= $ch;
                continue;
            }

            if ($ch === '"' && ! $inSingle && ! $inBacktick) {
                $escaped = $i > 0 && $sql[$i - 1] === '\\';
                if (! $escaped) {
                    $inDouble = ! $inDouble;
                }
                $buffer .= $ch;
                continue;
            }

            if ($ch === '`' && ! $inSingle && ! $inDouble) {
                $inBacktick = ! $inBacktick;
                $buffer .= $ch;
                continue;
            }

            if ($ch === ';' && ! $inSingle && ! $inDouble && ! $inBacktick) {
                $stmt = trim($buffer);
                if ($stmt !== '') {
                    $statements[] = $stmt;
                }
                $buffer = '';
                continue;
            }

            $buffer .= $ch;
        }

        $tail = trim($buffer);
        if ($tail !== '') {
            $statements[] = $tail;
        }

        return $statements;
    }
}

