# Yii2 file/image upload to Cloudinary component and behavior for ActiveRecord #
 
This package contains Component and Behavior for upload and 
display files from [Cloudinary](https://cloudinary.com/) service.
 
## Installation ##

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

    php composer.phar require nikosid/yii2-coudinary "dev-master"

or add

    "nikosid/yii2-coudinary": "dev-master"

to the `require` section of your composer.json.

## CloudinaryComponent ##

You need to configure cloudinary component in your application config.

```php
    'components' => [
        'cloudinary' => [
            'class' => CloudinaryComponent::class,
            'cloud_name' => 'YOUR_CLOUD_NAME',
            'api_key' => 'YOUR_API_KEY',
            'api_secret' => 'YOUR_API_SECRET',
            'cdn_subdomain' => true,//optional
            'useSiteDomain' => false,
        ],
    ],
```

By setting **$useSiteDomain** to true you can make URLs to your doman
and than proxy them to cloudinary server. By default it's false.

###Example of nginx config for forward traffic to cloudinary server ###

```
    location /YOUR_CLOUD_NAME/ {
        proxy_pass https://res.cloudinary.com;
        proxy_set_header Host res.cloudinary.com;
    }
```
 
## CloudinaryBehavior ##

This behavior allows you to add file uploading logic with ActiveRecord behavior.

### Usage ###
Attach the behavior to your model class:
```php
    public function behaviors()
    {
        return [
            'cloudynary' => [
                'class' => CloudinaryBehavior::class,
                'attribute' => 'picture',
                'publicId' => Yii::$app->name . '/articles/main{id}',
                'thumbs' => [
                    'large' => ['secure' => true, 'width' => 848, 'height' => 536, 'crop' => 'fill'],
                    'medium' => ['secure' => true, 'width' => 555, 'height' => 536, 'crop' => 'fill'],
                    'small' => ['secure' => true, 'width' => 130, 'height' => 125, 'crop' => 'fill'],
                ],
            ],
        ];
    }
```

Add validation rule:
```php
    
    //For file upload    
    public function rules()
    {
        return [
            ['picture', 'image', 'extensions' => 'jpg, jpeg, gif, png', 'on' => ['insert', 'update']],   
        ];
    }
    
    //Or for url type field    
    public function rules()
    {
        return [
            ['url_picture', 'url',],   
        ];
    }
```

Example view file for upload file from local storage:
```php
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'picture')->fileInput() ?>
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
```

Example view file for upload file from url:
```php
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'url_picture')->textInput() ?>
    <div class="form-group">
        <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
    </div>
<?php ActiveForm::end(); ?>
```

File should be uploading fine.


You can also get generated thumbnail image url:
```php
    echo $model->getThumb('medium');
```

### Both type of upload
You can also specify few attributes in priotiry order.

```php
    public function behaviors()
    {
        return [
            'cloudynary' => [
                'class' => CloudinaryBehavior::class,
                'attribute' => 'picture,picture_url',
                'publicId' => Yii::$app->name . '/articles/main{id}',
                'thumbs' => [
                    'large' => ['secure' => true, 'width' => 848, 'height' => 536, 'crop' => 'fill'],
                    'medium' => ['secure' => true, 'width' => 555, 'height' => 536, 'crop' => 'fill'],
                    'small' => ['secure' => true, 'width' => 130, 'height' => 125, 'crop' => 'fill'],
                ],
            ],
        ];
    }
```
It means if user upload `picture` Cloudinary get it, but if not, we also check picture_url and try to upload it 

## Licence ##

MIT
    
## Links ##

* [Source code on GitHub](https://github.com/nikosid/yii2-cloudinary)
* [Composer package on Packagist](https://packagist.org/packages/nikosid/yii2-cloudinary)
