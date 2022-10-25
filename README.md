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
        'passport', // Название модели
        new Client(), // GuzzleHTTP клиент
        '/home/user/IAMToken.txt', // Путь к файлу для распознавания
        'ru' // Язык документа, по умолчанию 'ru'
    );
```

### Создание запроса

На выходе получаем класс - набор параметров распознанного документа.

```injectablephp
// Формирование ключей и инициализация объекта
$ocrVisionYandexCloud = new ApiClient(
    '', // oAuth токен
        '', // folderId
        'passport', // Название модели
        new Client(), // GuzzleHTTP клиент
);

// Отправка запроса с путём к файлу для распознаванию, в ответ DTO
$result = $ocrVisionYandexCloud->processDetection('/home/user/passport.jpg');

print_r($result);
```

Доступны следующие модели DTO в зависимости от переданной модели:
- **Passport**
- **DriverLicenseBack**
- **DriverLicenseFront**


Документация по использованию распознавания текста сервисом Vision: 
https://cloud.yandex.ru/docs/vision/operations/ocr/text-detection