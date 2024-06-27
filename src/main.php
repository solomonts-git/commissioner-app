<?php

require __DIR__ . '/../vendor/autoload.php';

use Solomon\Commissioner\Calculator;

// Read CSV
$inputFile = $argv[1];
$rows = array_map('str_getcsv', file($inputFile));

$header = array_shift($rows);
$operations = [];
foreach ($rows as $row) {
    $operations[] = array_combine($header, $row);
}

// Calculate fees
$calculator = new Calculator($operations);
$fees = $calculator->calculateFees();

foreach ($fees as $fee) {
    echo $fee . PHP_EOL;
}
