<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Posts';
$this->params['breadcrumbs'][] = $this->title;


echo LinkPager::widget([
    'pagination' => $pagination,
]);
?>


<div class="post-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Post', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php foreach($posts as $post): ?>
        <div class="post-list well well-lg" xmlns="http://www.w3.org/1999/html">

            <h1><?= Html::a(Html::encode(ucfirst($post->title)), [Url::to(['post/view', 'id' => $post->id])]) ?></h1>


            <p>
                Posted by <strong> <?= Html::a(Html::encode($post->user->username), ['/user/profile/show', 'id' => Yii::$app->user->id], ['target' => '_blank'])  ?> </strong>
                at <strong> <?= Html::encode($post->created_at) ?> </strong>
            </p>

            <div>
                <?= HtmlPurifier::process($post->content) ?>
            </div>

            <?php if ($post->user->id == Yii::$app->user->id || Yii::$app->user->identity->isAdmin): ?>
                <p style="margin-top: 14px;">
                    <?= Html::a('Update', ['update', 'id' => $post->id], ['class' => 'btn btn-primary']) ?>
                    <?= Html::a('Delete', ['delete', 'id' => $post->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
            <?php endif; ?>

        </div>
    <?php endforeach; ?>

</div>
