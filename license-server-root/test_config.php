<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$providers = config('app.providers');
echo "Providers count: " . (is_array($providers) ? count($providers) : 'NULL') . "\n";

if (is_array($providers)) {
    foreach (array_slice($providers, 0, 5) as $p) echo " - $p\n";
}
