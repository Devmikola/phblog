<?php

namespace app\controllers;

use Yii;
use app\models\Comment;
use app\models\Post;
use yii\helpers\Url;
use yii\web\Response;

class CommentController extends \yii\web\Controller
{
    public function actionIndex($post_id)
    {
        $post = Post::findOne($post_id);

        \Yii::$app->response->format = Response::FORMAT_JSON;

        if($post)
        {
            return ['comments' => $this->renderPartial('index', ['comments' => $post->getParentComments()])];
        } else {
            return;
        }

    }

    /**
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @param $post_id
     * @param $parent_id
     */
    public function actionCreate($post_id, $parent_id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

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
        \Yii::$app->response->format = Response::FORMAT_JSON;

        $model = Comment::findOne($id);
        if(!$model)
        {
            return ['success' => false];
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['success' => true, 'comment-content' => $model->content,
                'updated-at' => Comment::findOne($id)->updated_at];
        } else {
            return ['success' => false];
        }
    }

    public function actionDelete($id)
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;

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

    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
