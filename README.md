# OCR Vision Yandex Cloud

## Описание

OCR Vision Yandex Cloud предоставляет API для загрузки документов на обработку и получение результатов распознавания.
Для работы с API системы требуется получить ключи доступа.

## Установка

Добавить в `composer.json`

```
"repositories": [
    ...
    {
      "type": "vcs",
      "url": "https://github.com/dev-moneyfriends-ru/ocr_vision_yandex_cloud.git"
    }
  ]
```

Запустить

```
composer require --prefer-dist mf-team/ocr_vision_yandex_cloud dev-master
```

или добавить

```
"mf-team/ocr_vision_yandex_cloud": "dev-master"
```

в `composer.json`.

## Использование

Создать новый экземпляр класса

```injectablephp
$ocrVisionYandexCloud = VisionYandexGatewayFactory::instanceClient(
        '', // oAuth токен
        '', // folderId
        new Passport(), // Шаблон обрабатываемого документа
        new Client(), // GuzzleHTTP клиент
        '/home/user/IAMToken.txt', // Путь к файлу для распознавания
    );
```

### Создание запроса

На выходе получаем класс - набор параметров распознанного документа.

```injectablephp
// Формирование ключей и инициализация объекта
use GuzzleHttp\Client;
use mfteam\ocrVisionYandexCloud\templates\Passport;
use mfteam\ocrVisionYandexCloud\VisionYandexGatewayFactory;

$api = VisionYandexGatewayFactory::instanceClient(
    '',
    '',
    new Client(),
    new Passport()
);

try {
    $arrayOfDto = $api->processDetection('/tmp/1234.jpg');
} catch (ocrVisionYandexCloud\exceptions\FillTemplateException $e) {
    echo 'Распознать документ не удалось' . PHP_EOL;
}

var_dump($arrayOfDto->toArray());
```

Доступны следующие модели DTO в зависимости от переданной модели:
- **Passport**
- **DriverLicenseBack**
- **DriverLicenseFront**


Документация по использованию распознавания текста сервисом Vision: 
https://cloud.yandex.ru/docs/vision/operations/ocr/text-detection