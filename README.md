# Homeful KwYC Check Package

[![Latest Version on Packagist](https://img.shields.io/packagist/v/homeful/kwyc-check.svg?style=flat-square)](https://packagist.org/packages/homeful/kwyc-check)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/homeful/kwyc-check/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/homeful/kwyc-check/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/homeful/kwyc-check/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/homeful/kwyc-check/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/homeful/kwyc-check.svg?style=flat-square)](https://packagist.org/packages/homeful/kwyc-check)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require homeful/kwyc-check
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="kwyc-check-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="kwyc-check-config"
```

This is the contents of the published config file:

```php
return [
    'campaign_url' => env('CAMPAIGN_URL', 'https://kwyc-check.net/campaign-checkin/9ccef822-4209-4e0a-bb40-232da5cafdf1')
];
```
```ENV Setup```
    'HYPERVERGE_APP_BASEURL' = ""
    'HYPERVERGE_APP_ID' = ""
    'HYPERVERGE_APP_KEY' = ""
    'HYPERVERGE_DEFAULT_FILESTORE' = ""
    'ENGAGESPARK_APIKEY' = ""
    'ENGAGESPARK_ORG_ID' = ""
```ENV Setup```

There are api end points.
- the payload for process-lead is webhook post from kwyc-check via pipedream
- the payload for attach-media is an array of urls point to images
- the payload for generate-qr is an array of inputs with default values

```php
Route::post('process-lead', ProcessLeadController::class)
    ->prefix('api')
    ->middleware('api')
    ->name('process-lead');

Route::post('attach-media/{lead}', AttachLeadMediaController::class)
    ->prefix('api')
    ->middleware('api')
    ->name('attach-media');
Route::post('generate-qr', GenerateQRCodeController::class)
    ->prefix('api')
    ->middleware('api')
    ->name('generate-qr');
Route::post('validate/id',  [HypervergeController::class, 'validate_id'])
    ->prefix('api')
    ->middleware('api')
    ->name('id validation');
Route::post('validate/id/base64',  [HypervergeController::class, 'validate_id'])
    ->prefix('api')
    ->middleware('api')
    ->name('id validation');
Route::post('check/liveliness',  [HypervergeController::class, 'validate_live_url'])
    ->prefix('api')
    ->middleware('api')
    ->name('liveliness validation');
Route::post('check/liveliness/base64',  [HypervergeController::class, 'validate_live_base64'])
    ->prefix('api')
    ->middleware('api')
    ->name('liveliness validation');
Route::post('check/faceverify',  [HypervergeController::class, 'face_verify'])
    ->prefix('api')
    ->middleware('api')
    ->name('face match verification');
Route::post('check/faceverify/base64',  [HypervergeController::class, 'face_verify_base64'])
    ->prefix('api')
    ->middleware('api')
    ->name('face match verification');    
Route::post('create/contact',  [EngageSparkController::class, 'formatContactBody'])
    ->prefix('api')
    ->middleware('api')
    ->name('face verification');    
```

To get the data from lead
```php

use Homeful\KwYCCheck\Data\LeadData;
use Homeful\KwYCCheck\Models\Lead;

$lead = Lead::factory()->forContact()->create();
$data = LeadData::fromModel($lead);
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="kwyc-check-views"
```

## Usage

```php
use Homeful\KwYCCheck\Facades\KYC;


$svg = KYC::generateCampaignQRCOde(query_params: ['code' => 'ABC-123','identifier'=>'DEF-456','choice'=>'GHI-789']);

echo $svg; 
//qr code points to https://kwyc-check.net/campaign-checkin/9ccef822-4209-4e0a-bb40-232da5cafdf1?code=ABC-111&identifier=DEF-222&choice=GHI-333

```validate/id```
    $jsonInput =[{
        "imageURL" : "", //should be inside storage folder -- ex. app/public
        "referenceCode":"", //alpha-numeric
        "countryId":"",//country code check documentation link for list https://documentation.hyperverge.co/OCR_country_docs_list
        "documentId":""//id type check documentation link for list https://documentation.hyperverge.co/OCR_country_docs_list
    }];
    ```or```
    $jsonInput =[{
        "base64Img" : "", //base64 image
        "referenceCode":"", //alpha-numeric
        "countryId":"",//country code check documentation link for list https://documentation.hyperverge.co/OCR_country_docs_list
        "documentId":""//id type check documentation link for list https://documentation.hyperverge.co/OCR_country_docs_list
    }];
```check/liveliness```
    $jsonInput =[{
        "imageURL":"",//should be inside storage folder -- ex. app/public
        "referenceCode":"",//alpha-numeric
        }];

```check/faceverify```
    $jsonInput =[{
        "imageURL":"",//should be inside storage folder -- ex. app/public
        "referenceCode":"",//alpha-numeric
        "imagePath":"",//path inside storage -- ex. app/public
        "type":"" //selfie or id
        }];
    ```or```    
    $jsonInput =[{
        "base64Img":"",//base64 image
        "referenceCode":"",//alpha-numeric
        "imagePath":"",//path inside storage -- ex. app/public
        "type":"" //selfie or id
    }];


```Engagespark```
    use Homeful\KwYCCheck\Http\Controllers\EngageSparkController;
    ```create contact```
    $jsonInput = [{
        "mobile": "", //number
        "firstName": "", //alpha-numeric
        "lastName": "",//alpha-numeric
        "groupId": [] ////alpha-numeric array
    }]
    EngageSparkController::saveContact($jsonInput);
```
## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Anais Santos](https://github.com/anais-enclavewrx)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
