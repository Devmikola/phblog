<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php foreach($comments as $comment): ?>
    <div class="comment-view" style="margin-bottom: 20px;" id_in_post="<?= $comment->id_in_post?>" parent_id="<?= $comment->parent_id?>">
        <p class="created-updated-at">
            <?= "#" . $comment->id_in_post ?> posted at <strong> <?= Html::encode($comment->created_at) ?> </strong>
            <?php if ($comment->created_at != $comment->updated_at): ?>
                <br/>Updated at <strong class="updated-at"> <?= Html::encode($comment->updated_at) ?> </strong>
            <?php endif; ?>
        </p>

        <div class="row">
            <div class="col-sm-6 col-md-1" style="text-align: center;">
                <img src="http://gravatar.com/avatar/<?= $comment->user->profile->gravatar_id ?>?s=100" alt="" class="img-rounded img-responsive" style="margin: 0;"/>
                <?= Html::a(Html::encode($comment->user->username), ['/user/profile/show', 'id' => Yii::$app->user->id], ['target' => '_blank'])  ?>
            </div>
            <?php if($comment->content):?>
                <div class="col-sm-6 col-md-11 well well-lg">
                    <?php if ($comment->user->id == Yii::$app->user->id || Yii::$app->user->identity->isAdmin): ?>

                        <?php $form = ActiveForm::begin([
                            'action' => Url::to(['comment/update', 'id' => $comment->id]),
                            'id' => 'update-comment-form-' . $comment->id,
                            'options' => ['class' => 'update-comment-form display-none']
                        ]); ?>

                            <?= $form->field($comment, 'content')->textarea(['rows' => 6])->label('') ?>

                            <div class="form-group">
                                <?= Html::submitButton('Update', ['class' => 'send-button btn btn-success']) ?>
                            </div>

                        <?php ActiveForm::end(); ?>
                    <?php endif; ?>

                    <p class="comment-content"><?= HtmlPurifier::process($comment->content) ?></p>

                </div>
            <?php else: ?>
                <div class="col-sm-6 col-md-11 well well-lg">
                    This comment was deleted and frozen.<br/>
                    Function answer no longer available for it.
                </div>
            <?php endif; ?>
        </div>

        <?php if($comment->content):?>
            <?php if ($comment->user->id == Yii::$app->user->id || Yii::$app->user->identity->isAdmin): ?>
                <p style="margin-top: 14px; display: inline;">
                    <?= Html::a('Edit', null, ['class' => 'btn btn-primary update-button']) ?>
                    <?= Html::a('Discard', null, ['class' => 'display-none btn btn-primary discard-button']) ?>
                    <?= Html::a('Delete', ['comment/delete', 'id' => $comment->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Are you sure you want to delete this item ?',
                            'method' => 'post',
                        ],
                    ]) ?>
                </p>
            <?php endif; ?>
            <p style="display: inline; margin-top: 14px;">
                <?= Html::button('Answer', ['class' => 'answer btn btn-success',
                    'id_in_post' => $comment->id_in_post,
                    'comment-id' => $comment->id,
                    'parent_id' => $comment->parent_id
                ]) ?>
            </p>
        <?php endif; ?>
    </div>

    <?php $child_comments = $comment->getChildComments() ?>
    <?php if($child_comments): ?>
        <div class="child-comments" style="margin: 20px 0px 0px 40px; padding-left: 20px; border-left: 1px solid #2e2e2e;">
            <?php echo $this->renderFile('@app/views/comment/index.php', ['comments' => $child_comments])?>
        </div>
    <?php endif; ?>


<?php endforeach; ?>
