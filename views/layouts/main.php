<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=27544797-3131-4759-9f4b-54f17c827eb2&lang=ru_RU" type="text/javascript"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
<!--<div class="loading-layout">
    <div class="wrapper"></div>
    <img id="loading" src="../web/yan/img/loading-rt.svg" alt="">
</div>-->

<div class="bbb" style="left:20%; display: flex; justify-content: flex-start; min-height: 50px; position: absolute; z-index: 100; top: 0; width: 80%">
    <div class="layout" style="width: 100%; height: 100%; position:absolute; opacity: 0.9"></div>
    <button class="btn btn-success back" style="z-index: 12321; width: 8vw;">&larr; Назад</button>
    <span class=""  style="display: block; font-size: 2.5vh; font-weight: bold; color: black; z-index: 123123123;"></span>
</div>
<div class="wrap">
    <?php
/*    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [
            ['label' => 'Home', 'url' => ['/site/index']],
            ['label' => 'About', 'url' => ['/site/about']],
            ['label' => 'Contact', 'url' => ['/site/contact']],
            Yii::$app->user->isGuest ? (
                ['label' => 'Login', 'url' => ['/site/login']]
            ) : (
                '<li>'
                . Html::beginForm(['/site/logout'], 'post')
                . Html::submitButton(
                    'Logout (' . Yii::$app->user->identity->username . ')',
                    ['class' => 'btn btn-link logout']
                )
                . Html::endForm()
                . '</li>'
            )
        ],
    ]);
    NavBar::end();*/
    ?>

    <div class="container-fluid">
        <?= $content ?>
    </div>
</div>



<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
