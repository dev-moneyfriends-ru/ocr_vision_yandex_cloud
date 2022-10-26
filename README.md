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
composer require --prefer-dist mf-team/ocr-vision-yandex-cloud dev-main
```

или добавить

```
"mf-team/ocr-vision-yandex-cloud": "dev-main"
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

Доступны следующие Шаблоны в зависимости от переданной модели:
- **Passport**
- **DriverLicenseBack**
- **DriverLicenseFront**

### Возможные исключения

-------------
```
AccessDeniedException       Ошибка доступа по токену
FillTemplateException       Ошибка заполнения шаблона данными
GetIAMTokenException        Ошибка получения IAM токена
GetTextDetectionException   Ошибка запроса на распознавание документа
IAMFileException            Ошибка при обращении к файлу содержащему IAM откен
ImageFileException          Ошибка работы с изображением
```

Документация по использованию распознавания текста сервисом Vision: 
https://cloud.yandex.ru/docs/vision/operations/ocr/text-detection