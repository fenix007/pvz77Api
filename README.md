# pvz77Api
Integration with pvz77 api

# Install

```bash
git clone https://github.com/fenix007/pvz77Api.git
cd pvz77Api
cp pvzConfig.smpl.php pvzConfig.php #the edit pvzConfig.php
```

Example of how to use (createParcel method)

```php
<?php
require_once (__DIR__ . '/vendor/autoload.php');
date_default_timezone_set('Europe/Moscow');

$pvzConfig = include __DIR__ . '/pvzConfig.php';
$config = Config::create($pvzConfig);

$pvz77 = new Pvz77($config);

if ($trackNumber = $_GET['track_number']) {
    var_dump($trackNumber); exit;
}

$parcelRecipient = \SpExt\Pvz\Model\ParcelRecipient::create([
    'nick'    => 'test_nick',
    'name'    => 'test_name',
    'phone'   => 'test_phone',
    'email'   => 'test_email@test.tt',
    'address' => 'test_address'
]);

$parcelSender = \SpExt\Pvz\Model\ParcelSender::create([
    'nick'    => 'test_nick',
    'name'    => 'test_name',
    'phone'   => 'test_phone',
    'email'   => 'test_email@test.tt',
    'address' => 'test_address'
]);

$parcel = \SpExt\Pvz\Model\Parcel::create([
    'recipient' => $parcelRecipient,
    'sender' => $parcelSender,
    'delivery_point' => 10
]);

$result = $pvz77->createParcel($parcel);
```
