<?php

use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use yii\helpers\Url;


/* @var $this yii\web\View */
/* @var $model app\models\Post */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$this->registerJsFile('/js/view.js', ['position' => yii\web\View::POS_END]);
?>
<div class="post-view" xmlns="http://www.w3.org/1999/html">

    <h1><?= ucfirst(Html::encode($this->title)) ?></h1>

    <p>
        Posted by <strong> <?= Html::a(Html::encode($model->user->username), ['/user/profile/show', 'id' => Yii::$app->user->id], ['target' => '_blank'])  ?> </strong>
        at <strong> <?= Html::encode($model->created_at) ?> </strong>
        <?php if ($model->created_at != $model->updated_at): ?>
            <br/>Updated at <strong> <?= Html::encode($model->updated_at) ?> </strong>
        <?php endif; ?>
    </p>

    <div class="row">
        <div class="col-sm-6 col-md-1" style="text-align: center;">
            <img src="http://gravatar.com/avatar/<?= $model->user->profile->gravatar_id ?>?s=100" alt="" class="img-rounded img-responsive" style="margin: 0;"/>
            <?= Html::encode($model->user->username) ?>
        </div>
        <div class="col-sm-6 col-md-11 well well-lg" style="background-color: #66afe9;">
            <?= HtmlPurifier::process($model->content) ?>
        </div>

    </div>

    <?php if ($model->user->id == Yii::$app->user->id || Yii::$app->user->identity->isAdmin): ?>
        <p style="display: inline; margin-top: 14px;">
            <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
            <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                'class' => 'btn btn-danger',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this item ?',
                    'method' => 'post',
                ],
            ]) ?>
        </p>
    <?php endif; ?>
    <p style="display: inline; margin-top: 14px;">
        <?= Html::button('Answer', ['class' => 'default-answer btn btn-success']) ?>
    </p>



</div>

<div class="comments" style="margin-top: 30px;">
    <?= $this->render('//comment/index', ['comments' => $model->getParentComments()])?>
</div>

<div class="comment-form">

    <?php $form = ActiveForm::begin([
        'action' => Url::to(['comment/create', 'post_id' => $model->id, 'parent_id' => 0]),
        'id' => 'create-comment-form'
    ]); ?>

        <?= $form->field($create_new_comment, 'content', ['inputOptions' => ['id' => 'create-comment-content', 'class' => 'form-control']])->textarea(['rows' => 6])->label('') ?>

        <div class="form-group">
            <?= Html::submitButton('Send', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
