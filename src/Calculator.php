<?php

namespace Solomon\Commissioner;

class Calculator
{
    private $currencyConverter;
    private $operations;

    public function __construct($operations)
    {
        $this->currencyConverter = new CurrencyConverter();
        $this->operations = $operations;
    }

    public function calculateFees()
    {
        $fees = [];
        $weeklyWithdrawals = [];

        foreach($this->operations as $operation){
            $date = $operation['date'];
            $userId = $operation['user_id'];
            $userType = $operation['user_type'];
            $type = $operation['type'];
            $amount = $operation['amount'];
            $currency = $operation['currency'];

            if ($type === 'deposit') {
                $fees[] = $this->calculateDepositFee($amount);
            } elseif ($type === 'withdraw') {
                if($userType === 'private') {
                    $fees[] = $this->calculatePrivateWithdrawFee($userId, $amount, $currency, $date, $weeklyWithdrawals);
                } else {
                    $fees[] = $this->calculateBusinessWithdrawFee($amount);
                }
            }
        }

        return $fees;
    }
     
    private function calculateDepositFee($amount)
    {
        return ceil($amount*0.0003*100) / 100;
    }

    private function calculatePrivateWithdrawFee($userId,$amount,$currency,$date,$weeklyWithdrawals)
    {

        // Check the currency type EUR and change if needed
        $amountInEur = $this->currencyConverter->convertToEur($amount,$currency);

        //Initialize user withdrawals tracking
        if (!isset($weeklyWithdrawals[$userId])) {
            $weeklyWithdrawals[$userId] = [];
        }

        // Get week number

        $weekNumber = date('oW', strtotime($date));

        if(!isset($weeklyWithdrawals[$userId][$weekNumber])) {
            $weeklyWithdrawals[$userId][$weekNumber] = [
                'count' => 0,
                'total' => 0.0,
            ];
        }

        $userWeekData = &$weeklyWithdrawals[$userId][$weekNumber];

        // Apply free withdrawal rules
        if ($userWeekData['count'] < 3 && ($userWeekData['total'] + $amountInEur) <= 1000.00) {
            $userWeekData['count']++;
            $userWeekData['total'] += $amountInEur;
            return 0.0;
        }

        $userWeekData['count']++;
        $excessAmount = max(0, $amountInEur + $userWeekData['total'] - 1000.00);

        if ($excessAmount > 0) {
            $userWeekData['total'] = 1000.00;
        }

        $feeInEur = $excessAmount * 0.003;
        // print_r($userWeekData);
        // Convert fee back to original currency
        $fee = $this->currencyConverter->convertFromEur($feeInEur,$currency);
        return ceil($fee * 100) / 100;
    }

    private function calculateBusinessWithdrawFee($amount){
        return ceil($amount * 0.005 * 100) / 100;
    }
}