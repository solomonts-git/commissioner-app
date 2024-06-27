<?php

namespace Solomon\Commissioner\Tests;

use PHPUnit\Framework\TestCase;
use Solomon\Commissioner\Calculator;

class CommissionFeeTest extends TestCase
{
    public function testCommissionFeeCalculation()
    {
        $operations = [
            ['date' => '2014-12-31', 'user_id' => 4, 'user_type' => 'private', 'type' => 'withdraw', 'amount' => 1200.00, 'currency' => 'EUR'],
            ['date' => '2015-01-01', 'user_id' => 4, 'user_type' => 'private', 'type' => 'withdraw', 'amount' => 1000.00, 'currency' => 'EUR'],
            ['date' => '2016-01-05', 'user_id' => 4, 'user_type' => 'private', 'type' => 'withdraw', 'amount' => 1000.00, 'currency' => 'EUR'],
            ['date' => '2016-01-05', 'user_id' => 1, 'user_type' => 'private', 'type' => 'deposit', 'amount' => 200.00, 'currency' => 'EUR'],
            ['date' => '2016-01-06', 'user_id' => 2, 'user_type' => 'business', 'type' => 'withdraw', 'amount' => 300.00, 'currency' => 'EUR'],
            ['date' => '2016-01-06', 'user_id' => 1, 'user_type' => 'private', 'type' => 'withdraw', 'amount' => 30000, 'currency' => 'JPY'],
            ['date' => '2016-01-07', 'user_id' => 1, 'user_type' => 'private', 'type' => 'withdraw', 'amount' => 1000.00, 'currency' => 'EUR'],
            ['date' => '2016-01-07', 'user_id' => 1, 'user_type' => 'private', 'type' => 'withdraw', 'amount' => 100.00, 'currency' => 'USD'],
            ['date' => '2016-01-10', 'user_id' => 1, 'user_type' => 'private', 'type' => 'withdraw', 'amount' => 100.00, 'currency' => 'EUR'],
            ['date' => '2016-01-10', 'user_id' => 2, 'user_type' => 'business', 'type' => 'deposit', 'amount' => 10000.00, 'currency' => 'EUR'],
            ['date' => '2016-01-10', 'user_id' => 3, 'user_type' => 'private', 'type' => 'withdraw', 'amount' => 1000.00, 'currency' => 'EUR'],
            ['date' => '2016-02-15', 'user_id' => 1, 'user_type' => 'private', 'type' => 'withdraw', 'amount' => 300.00, 'currency' => 'EUR'],
            ['date' => '2016-02-19', 'user_id' => 5, 'user_type' => 'private', 'type' => 'withdraw', 'amount' => 3000000, 'currency' => 'JPY'],
        ];

        $calculator = new Calculator($operations);
        $fees = $calculator->calculateFees();

        $expectedFees = [
            0.60, 3.00, 0.00, 0.06, 1.50, 0, 0.70, 0.30, 0.30, 3.00, 0.00, 0.00, 8612
        ];
        $expectedFees1 = [
            0.6, 0, 0, 0.06, 1.5, 0, 0, 0, 0, 3, 0, 0, 8611.42
        ];
        $this->assertEquals($expectedFees1, $fees);
    }
}
