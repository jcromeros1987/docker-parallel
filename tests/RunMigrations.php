<?php

require __DIR__ . '/../api/vendor/autoload.php';

$app = require_once __DIR__ . '/../api/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Ejecutando migraciones...\n";
Artisan::call('migrate');
echo "Migraciones completadas.\n"; 