# Commission Fee Calculation

## Requirements

- PHP 7.4+
- Composer

## Setup

1. Clone the repository.
2. Run `composer install` to install dependencies and set up autoloading.

## Running the Application

```sh
php src/main.php input/input.csv

```
## Running the Test
1. for testing please uncomment _**line 18**_ from **CurrencyConverter** class and comment _**line 19**_
   
   - **line 18** // $json = file_get_contents('rate.txt', FILE_USE_INCLUDE_PATH);
   
   - **line 19**  $json = file_get_contents('https://developers.paysera.com/tasks/api/currency-exchange-rates');


```sh
composer test
```

## Requirement with pseudocode
```
1. calculate commision on the currency of operation for withdraw or deposit
2. make commission fee to currency's decimal places. i.e. ceil it to 2 digit format

Deposit Rule
3. All deposis are charged 0.03% of deposit amount

Withdraw rules
4. if type==private
        4.1. the first 3 withdraw less than 1000EUR is free. the fourth commissioned to 0.3% per week.
        4.2. if withdraw exceeded from 1000EUR the difference is commissioned
    if type== business
        4.1. commissioned 0.5% from withdrawn amount

```


## Comment on the data set given

```
➜  cat input.csv 
2014-12-31,4,private,withdraw,1200.00,EUR
2015-01-01,4,private,withdraw,1000.00,EUR
2016-01-05,4,private,withdraw,1000.00,EUR

➜  php script.php input.csv
0.60
3.00
0.00


the dataset given for testing show some inconsistencies when evaluated based on the requirement given. Here is the explanation. 

In the requirement given the first 3 transactions less than or equal to 1000EUR is free of charge but on the given output, it is calculated even though it is in different week. 
0.60(Correct)
3.00(Incorrect) -2015-01-01 - it is in new week it should be 0.00
0.00(Correct)- 2016-01-05

```
## Functions Used for FeeCommissioner Traditional Program
| No                   | Functions Used                                                                      | Class                | Purpose of its use                                                         |
| -------------------- | ----------------------------------------------------------------------------------- | -------------------- | -------------------------------------------------------------------------- |
| 1                    | calculateFees()                                                                     | Calculator           | Used to calculate private and business charge fee for deposit and withdraw |
| 2                    | calculateDepositFee($amount)                                                        | Calculator           | Used to calculate Deposit fee - 0.03% of deposit                           |
| 3                    | calculatePrivateWithdrawFee( $userId, $amount, $currency,$date, $weeklyWithdrawals) | Calculator           | used to calcualte withdraw fee it may be 0.00 or 0.03                      |
| 4                    | calculateBusinessWithdrawFee($amount)                                               | Calculator           | Used to calculate withdraw fee it is always 0.05%                          |
| 5                    | fetchRates()                                                                        | CurrencyConverter    | Used to fetch exchange rates from api or local file rate.                  |
| 6                    | convertToEur()                                                                      | CurrencyConverter    | Used to convert currencies to Eur                                          |
| 7                    | convertFromEur                                                                      | CurrencyConverter    | Used to convert currencies from Eur to the original currency.              |
| PHP Built In            Methods |                                                                                     |                      |                                                                   |
| 8                    | array_map()                                                                         | inside main.php file | for mapping array values                                                   |
| 9                    | array_shift()                                                                       | inside main.php file | for returning the first row of array                                       |
| 10                   | array_combine()                                                                     | inside main.php file | for creating key:value pain in array                                       |
| 11                   | file_get_contents                                                                   | CurrencyConverter    | for converting file to string                                              |
| 12                   | json_decode                                                                         | CurrencyConverter    | to convert json file to array                                              |
| 13                   | json_last_error()                                                                   | CurrencyConverter    | access json last error                                                     |
| 14                   | assertEquals                                                                        | CommissionFeeTest    | phpunit method to test two values. in this case array's |



## Time to finish
In order to finish this project it takes 8-10 hours.

