<?php

namespace app\controllers;

use Yii;
use app\models\Comment;
use yii\helpers\Url;
use yii\web\Response;

class CommentController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
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
        $model = new Comment();
        $model->post_id = $post_id;
        $parent_id == 0 ? : $model->parent_id = $parent_id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['post/view', 'id' => $post_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        \Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['success' => true, 'comment-content' => $model->content, 'updated-at' => $this->findModel($id)->updated_at];
        } else {
            return ['success' => false];
        }
    }

    public function actionDelete($id)
    {
        $comment = Comment::findOne($id);
        Comment::updateAllCounters(['id_in_post' => -1], "id_in_post > $comment->id_in_post");
        Comment::updateAll(['parent_id' => $comment->parent_id], "parent_id = $comment->id");
        $comment->delete();

        return $this->redirect(Url::to(['post/view', 'id' => $comment->post_id]));
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
