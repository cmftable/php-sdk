<p align="center">
  <img src="https://images.cmft.io/987150097760522240/987268367343820800/987268367356399616/cmft-php-banner.png" />
  <br/><br/>
  <a href="https://slack-comfortable.herokuapp.com/"><img src="https://img.shields.io/badge/-Join%20Slack%20Community-67c0a1.svg?logo=slack" style="display:inline-block" /></a>
  <br/>
</p>


# PHP Development Kit
<p>
  
[![Build Status](https://travis-ci.org/cmftable/php-sdk.svg?branch=master)](https://travis-ci.org/cmftable/php-sdk)
<img src="https://img.shields.io/badge/License-MIT-blue.svg" /> 
<br/><br/>
</p>

## Installation

The Comfortable PHP SDK can be installed with [Composer](https://getcomposer.org/). 
Run this command:

```sh
composer require comfortable/php-sdk
```

## Usage

> **Note:** This version of the SDK requires PHP 5.6 or greater.

<br>

### Include the dependency:

```php
<?php
require_once __DIR__ . '/vendor/autoload.php'; // change path as needed

use Comfortable;
```

### Connect to your Repository and make your first request:

```php
$api = Comfortable\Api::connect('{repository-api-id}', '{api-key}');

try {
  // get all documents stored in comfortable (default limit: 25)
  $results = $api->getDocuments()->execute();  
} catch (\RuntimeException $e) {
  echo 'Comfortalbe SDK returned an error: ' . $e->getMessage();
  exit;
}
```
Complete documentation, installation instructions, and examples are available [here](https://docs.comfortable.io/sdks/php/installation).

## Tests
 1. Composer is a prerequisite for running the tests. Install composer globally, then run composer install to install required files.
 2. Create `phpunit.xml` from `phpunit.xml.dist` and edit it to add your credentials. Alternatively you can set your credentials as environment variables. For this you have to define `CMFT_REPOSITORY` and `CMFT_APIKEY`.
 3. The tests can be executed by running the following command from the root directory:
 ```bash
 ./vendor/bin/phpunit
 ```

## More information
 - [Developer Documentation](https://docs.comfortable.io)
 - [PHP SDK Documentation](https://docs.comfortable.io/sdks/php/installation)
 - [Changelog](https://github.com/cmftable/php-sdk/releases)

## Contributing
Pull requests are always welcome! 
<br/>

[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg?style=flat-square)](http://makeapullrequest.com)


## License
This repository is published under the [MIT](LICENSE) license.
