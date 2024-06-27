<?php

namespace Solomon\Commissioner;

class CurrencyConverter
{

    private $rates;

    public function __construct()
    {
        $this->rates = $this->fetchRates();
    }

    private function fetchRates()
    {
        // Read the file
        // $json = file_get_contents('rate.txt', FILE_USE_INCLUDE_PATH);
        $json = file_get_contents('https://developers.paysera.com/tasks/api/currency-exchange-rates');
    
        // Check if the file was read successfully
        if ($json === false) {
            // If not, output an error message
            echo "Failed to read the file.";
            return null;
        }
    
          
        // Decode the JSON
        $data = json_decode($json, true);
     
        // Check for JSON errors
        if (json_last_error() !== JSON_ERROR_NONE) {
            echo "JSON decode error: " . json_last_error_msg();
            return null;
        }
    
    
        // Return the 'rates' array
        if (isset($data['rates'])) {
            return $data['rates'];
        } else {
            echo "Rates key not found in the decoded JSON.";
            return null;
        }
    }
    

    public function convertToEur($amount, $currency)
    {
        if ($currency === 'EUR') {
            return $amount;
        }
        return $amount / $this->rates[$currency];
    }

    public function convertFromEur($amount, $currency)
    {
        if ($currency === 'EUR') {
            return $amount;
        }

        return $amount * $this->rates[$currency];
    }
}