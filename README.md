<p align="center">
    <a href="https://git.lsat.vn/packages/yii2-otp" target="_blank">
        <img src="https://lcs.com.vn/img/logo.svg" height="100px">
    </a>
    <h1 align="center">LSAT Yii2 OTP</h1>
    <br>
</p>

### How to install

```shell
docker-compose exec api composer require lsat/yii2-otp:~1.0.0
```
Or 
```shell
composer require lsat/yii2-otp:~1.0.0 
```
or Add

```json
"lsat/yii2-otp" : "~1.0.0"
```
to the require section of your application's `composer.json` file.

### Add components to web.php or console.php

```php
'components' => [
    'otp' => [
        'class' => lsat\otp\Otp::class,
        // 'totp' only now
        'algorithm' => lsat\otp\Otp::ALGORITHM_TOTP,

        // length of code
        'digits' => 6,

        //  Algorithm for hashing
        'digest' => 'sha1',

        // Label of application
        'label' => '1022_Bình Dương',

        // Uri to image (application icon)
//            'imgLabelUrl' => \yii\helpers\Url::to('\app\web\logo.php'),

        // Betwen 8 and 1024
        'secretLength' => 72,
        // Time interval in seconds, must be at least 1
        'interval' => 30,
        'issuer' => '1022_BD',
    ],
]
```

### Use Behavior
Set behavior in model
```php
<?php
...

'behavior' => [
    'otp' => [
        'class' => lsat\otp\behavior\OtpBehavior::className(),
        // Component name
        'component' => 'otp',
        
        // column|property name for get and set secure phrase
        //'secretAttribute' => 'secret'
        // column|property name for get code and confirm secret
        //'codeAttribute' => 'code'
        
        //Window in time for check authorithation (current +/- window*interval) 
        //'window' => 0
    ],
...
]
```
attachBehavior with dynamic form
```php
// create form or load secret form for each user
$dynamicModel = new yii\base\DynamicModel(['code','secret']);
$dynamicModel->addRule(['code'],'required');
$dynamicModel->addRule(['code'],'string', ['min' => 6]);
$dynamicModel->addRule(['secret'],'string');
// set secret attribute
$dynamicModel->secret = "YOURSECRET";
$dynamicModel->attachBehavior("otp", [
    'class' => OtpBehavior::class,
//            'secretAttribute' => "CustomSecretField",
//            'codeAttribute' => "CustomCodeField",
]);
// Load value code attribute
$code = Yii::$app->request->post("code");
$dynamicModel->code = $code;
// Validate otp code and secret attribute
if (!$dynamicModel->validate()) {
    var_dump($dynamicModel->errors);
}
```
### Widget for generate init QR-code.
Read more about QrParams in the [qrcode-library](https://github.com/2amigos/qrcode-library)
```php
<?php 
echo $form->field($model, 'secret')->widget(
            lsat\otp\widgets\OtpInit::class, [
            'component' => 'otp',

            // link text
            'link' => false,

            'QrParams' => [
                // pixels width
                'size' => 200,

                // margin around QR-code
                'margin' => 10,

                // Path to logo on image
                'logo' => Yii::getAlias("@app/web/icon.png"),

                // Width logo on image
                'logoWidth' => 50,

                // RGB color
                'foregroundColor' => [0, 0, 0],

                // RGB color
                'backgroundColor' => [255, 255, 255],

                // Qulity of QR: LOW, MEDIUM, HIGHT, QUARTILE
                'level' => ErrorCorrectionLevelInterface::HIGH,

                // Image format: PNG, JPG, SVG, EPS
                'type' => PngWriter::class,

                // Locale
                'encoding' => 'UTF-8',

                // Text on image under QR code
                'label' => '',

                // by default image create and save at Yii::$app->runtimePath . '/temporaryQR/'
//                            'outfile' => '/tmp/'.uniqid(),

                // save or delete after generate
                'save' => false,
            ]
        ])->label(false); 
?>
```


