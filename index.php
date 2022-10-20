<?php

namespace mfteam;

require_once('/home/cae/friends/vision-analyze-api/src/ApiClient.php');
require_once('/home/cae/friends/vision-analyze-api/vendor/autoload.php');

use mfteam\ocrVisionYandexCloud\ApiClient;

$api = new ApiClient(
    'y0_AgAAAAABcJupAATuwQAAAADRoRDgwdtzEfpoRKOvqvqOXxul2BUz-Ls',
    'b1gnl76pluc11pkm9klr',
    'passport',
    'ru'
);

$arrayOfDto = $api->processDetection('/tmp/ПАСПОРТ/passport_min.png');

print_r((array) $arrayOfDto);