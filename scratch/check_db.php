<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

if (Schema::hasColumn('users', 'fcm_token')) {
    echo "COLUMN fcm_token EXISTS\n";
} else {
    echo "COLUMN fcm_token MISSING\n";
}

$columns = DB::select('DESCRIBE users');
print_r($columns);
