<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>

<?php foreach($comments as $comment): ?>
    <div class="comment-view" id-in-post="<?= $comment->id_in_post?>" parent_id="<?= $comment->parent_id?>">
        <p class="created-updated-at">
            <?= "#" . $comment->id_in_post ?> posted at <strong> <?= Html::encode(Yii::$app->formatter->asDatetime($comment->created_at, 'php:H:i:s / d M Y')) ?> </strong>
            <?php if ($comment->created_at != $comment->updated_at): ?>
                <br/>Updated at <strong class="updated-at"> <?= Html::encode(Yii::$app->formatter->asDatetime($comment->updated_at, 'php:H:i:s / d M Y')) ?> </strong>
            <?php endif; ?>
        </p>

        <div class="row">
            <div class="col-sm-6 col-md-1 user-avatar">
                <img src="http://gravatar.com/avatar/<?= $comment->user->profile->gravatar_id ?>?s=100" alt="" class="img-rounded img-responsive user-avatar-img"/>
                <?= Html::a(Html::encode($comment->user->username), ['/user/profile/show', 'id' => Yii::$app->user->id], ['target' => '_blank'])  ?>
            </div>
            <?php if($comment->content):?>
                <div class="col-sm-6 col-md-11 well well-lg">
                    <?php if ($comment->user->id == Yii::$app->user->id || (Yii::$app->user->identity && Yii::$app->user->identity->isAdmin)): ?>

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
            <?php endif; ?>
        </div>

        <?php if($comment->content):?>
            <?php if ($comment->user->id == Yii::$app->user->id || (Yii::$app->user->identity && Yii::$app->user->identity->isAdmin)): ?>
                <p class="comment-actions">
                    <?= Html::a('Edit', null, ['class' => 'btn btn-primary update-button']) ?>
                    <?= Html::a('Discard', null, ['class' => 'display-none btn btn-primary discard-button']) ?>
                    <?= Html::a('Delete', null, [
                        'class' => 'delete-button btn btn-danger',
                        'link' => Url::to(['comment/delete', 'id' => $comment->id]),
                    ]) ?>
                </p>
            <?php endif; ?>
            <p class="comment-actions">
                <?= Html::button('Answer', ['class' => 'answer btn btn-success',
                    'id-in-post' => $comment->id_in_post,
                    'comment-id' => $comment->id,
                    'parent_id' => $comment->parent_id
                ]) ?>
            </p>
        <?php endif; ?>
    </div>

    <?php $child_comments = $comment->getChildComments() ?>
    <?php if($child_comments): ?>
        <div class="child-comments">
            <?php echo $this->renderFile('@app/views/comment/index.php', ['comments' => $child_comments]) ?>
        </div>
    <?php endif; ?>


<?php endforeach; ?>
