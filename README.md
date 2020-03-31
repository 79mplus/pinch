# Pinch PHP library
This library provides access to Pinch Payment Gateway API from applications written in PHP.

## Installation
You can install the library via composer. Run the following command
```composer require mplus/pinch```
To use the library include composer's autoload file
```require_once('vendor/autoload.php');```

## Usage

### Create API Client
```$client = new mplus\Pinch\PinchApi($merchant_id, $secret_key, $mode);```
$mode should be test/live

### Execute a real time payment
```$client->payment->execute($publishable_key, $card_no, $cvc, $expiry_month, $expiry_year, $card_holder_name, $email, $amount, $description);```
$description is optional.

### Create a scheduled payment
```$client->payment->schedule($payer_id, $transaction_date, $amount, $description)```
$description is optional.

### Add a payer
```$client->payer->add($email, $first_name, $last_name, $mobile);```
$last_name and $mobile are optional.

### Update a payer
```$client->payer->update($id, $email, $first_name, $last_name, $mobile);```
$last_name and $mobile are optional.


