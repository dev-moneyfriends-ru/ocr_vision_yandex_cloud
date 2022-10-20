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
$ocrVisionYandexCloud = new \mfteam\ocrVisionYandexCloud\ApiClient(
            'oAuthToken' => 'y0_AgAAAAABcJupAATuwQAAAADRoRDgwdtzEfpoRKOvqvqOXxul2BUz-Ls',
            'folderId' => 'b1gnl76pluc11pkm9klr',
            'model' => 'passport',
            'language' => 'ru'
        );
```

### Создание запроса

На выходе получаем массив DTO распознанных страниц.

```injectablephp
// Формирование ключей и инициализация объекта
$ocrVisionYandexCloud = new ApiClient(
    'y0_AgAAAAABcJupAATuwQAAAADRoRDgwdtzEfpoRKOvqvqOXxul2BUz-Ls', // oAuth токен
    'b1gnl76pluc11pkm9klr', // folderId
    'passport', // Название модели, по умолчанию 'passport'
    'ru' // Язык документа, по умолчанию 'ru'
);

// Отправка запроса с путём к файлу для распознаванию, в ответ массив DTO
$arrayOfDto = $ocrVisionYandexCloud->processDetection('/tmp/ПАСПОРТ/passport_min.png');

print_r($arrayOfDto);
```

Доступны следующие модели DTO в зависимости от переданной модели:
- **Passport**
- **DriverLicenseBack**
- **DriverLicenseFront**

### Доступные методы переопределения параметров объекта класса

```injectablephp
// Переопределение модели для распознавания
$ocrVisionYandexCloud->setModel('driver-license-back');

// Установить свой хост API распознавания документа
$ocrVisionYandexCloud->setHost('https://new-host.com');

// Установить свой метод API распознавания документа
$ocrVisionYandexCloud->setMethod('PUT');

// Установить свой хост API получения IAM токена
$ocrVisionYandexCloud->setIAMHost('https://new-iam-host.com');

// Установить свой метод API получения IAM токена
$ocrVisionYandexCloud->setIAMMethod('GET');
```


Документация по использованию распознавания текста сервисом Vision: 
https://cloud.yandex.ru/docs/vision/operations/ocr/text-detection