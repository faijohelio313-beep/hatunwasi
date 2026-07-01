<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
echo "Combos count: " . App\Models\Combo::count() . "\n";
echo "Products count: " . App\Models\Product::count() . "\n";
