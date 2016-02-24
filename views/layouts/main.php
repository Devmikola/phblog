<?php

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Phblog',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => [

            ['label' => 'Posts', 'url' => [Url::toRoute(['/post/index'])], 'visible' => !Yii::$app->user->isGuest],
            ['label' => 'Profile', 'url' => [Url::toRoute(['/user/profile/show', 'id' => Yii::$app->user->id])], 'visible' => !Yii::$app->user->isGuest],
            ['label' => 'Settings', 'url' => ['/user/settings/profile'], 'visible' => !Yii::$app->user->isGuest],
            ['label' => 'Registration', 'url' => ['/user/registration/register'], 'visible' => Yii::$app->user->isGuest],
            ['label' => 'AdminPanel', 'url' => ['/user/admin/index'], 'visible' => ! Yii::$app->user->identity ? false : Yii::$app->user->identity->isAdmin],
            Yii::$app->user->isGuest ?
                ['label' => 'Login', 'url' => ['/user/security/login']] :
                [
                    'label' => 'Logout [' . Yii::$app->user->identity->username . ']',
                    'url' => ['/user/security/logout'],
                    'linkOptions' => ['data-method' => 'post']
                ],
        ],
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; Phblog <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
