<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use app\models\Comment;
use app\models\Post;
use yii\web\Response;

class CommentController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'create'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->identity->isAdmin || $this->isUserAuthor()) {
                                return true;
                            }
                            return false;
                        }
                    ],
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        return parent::beforeAction($action);
    }

    public function actionIndex($post_id)
    {
        $post = Post::findOne($post_id);

        if($post)
        {
            return ['comments' => $this->renderPartial('index', ['comments' => $post->getParentComments()])];
        } else {
            return;
        }

    }

    public function actionCreate($post_id, $parent_id)
    {
        $model = new Comment();
        $model->post_id = $post_id;
        if($parent_id != 0 && !Comment::findOne($parent_id))
        {
            return ['success' => false];
        }
        $parent_id == 0 ? : $model->parent_id = $parent_id;


        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['success' => true, 'comment-id-in-post' => $model->id_in_post];
        } else {
            return ['success' => false];
        }
    }

    public function actionUpdate($id)
    {
        $model = Comment::findOne($id);
        if(!$model)
        {
            return ['success' => false];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['success' => true, 'comment-content' => $model->content,
                'updated-at' => Yii::$app->formatter->asDatetime(Comment::findOne($id)->updated_at, 'php:H:i:s / d M Y')];
        } else {
            return ['success' => false];
        }
    }

    public function actionDelete($id)
    {
        $comment = Comment::findOne($id);
        if(!$comment)
        {
            return ['success' => false];
        }

        Comment::updateAllCounters(['id_in_post' => -1], "id_in_post > $comment->id_in_post");
        Comment::updateAll(['parent_id' => $comment->parent_id], "parent_id = $comment->id");


        if($comment->delete())
        {
            return ['success' => true];
        } else {
            return ['success' => false];
        }
    }

    protected function isUserAuthor()
    {
        return Comment::findOne(Yii::$app->request->get('id'))->user_id == Yii::$app->user->id;
    }
}
