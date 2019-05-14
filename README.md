Google Tag Manager extension for the Yii2 framework
===================================================
Integration Google Tag Manager in your application

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist omnicorellc/yii2-google-tag-manager"*"
```

or add

```
"omnicorellc/yii2-google-tag-manager": "*"
```

to the require section of your `composer.json` file.


Usage
-----

1 Add this code in your *web* config file:
 ```php
 'bootstrap' => ['googleTagManager'],
 'components' => [
     'googleTagManager' => [
         'class' => 'ezoterik\googleTagManager\GoogleTagManager',
         'tagManagerId' => 'GOOGLE_TAG_MANAGER_ID', //Your Google Tag Manager ID without "GTM-" prefix
         'tagManagerPrefix' => 'GTM-', // gtm container id prefix
     ],
 ],
 ```

2 You can generate events:
 ```php
 Yii::$app->googleTagManager->dataLayerPushItemDelay('event', 'example_event');
 ```
3 Trigger render tag manager parts:
 ```php
 Yii::$app->getView()->trigger(\omnicorellc\googleTagManager\GoogleTagManager::EVENT_RENDER_DATA_LAYER);
 ```
 ```php
  Yii::$app->getView()->trigger(\omnicorellc\googleTagManager\GoogleTagManager::EVENT_RENDER_CONTAINER_JS);
 ```
 ```php
  Yii::$app->getView()->trigger(\omnicorellc\googleTagManager\GoogleTagManager::EVENT_RENDER_CONTAINER_FRAME);
 ```