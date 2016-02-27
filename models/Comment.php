<?php

namespace app\models;

use dektrium\user\models\User;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "comment".
 *
 * @property integer $id
 * @property integer $id_in_post
 * @property integer $parent_id
 * @property integer $user_id
 * @property integer $post_id
 * @property string $content
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Comment $parent
 * @property Comment[] $comments
 * @property Post $post
 * @property User $user
 */
class Comment extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'comment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_in_post', 'parent_id', 'user_id', 'post_id'], 'integer'],
            [['content'], 'required'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_in_post' => 'Id In Post',
            'parent_id' => 'Parent ID',
            'user_id' => 'User ID',
            'post_id' => 'Post ID',
            'content' => 'Content',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['user_id'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['user_id'],
                ],
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Comment::className(), ['id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::className(), ['parent_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if (!$this->id_in_post)
            {
                $max_id_in_post = Comment::find()->where(['post_id' => $this->post_id])->max('id_in_post');
                $max_id_in_post ? $this->id_in_post = $max_id_in_post + 1 : $this->id_in_post = 1;
            }

            return true;
        }
        return false;
    }

    public function getChildComments()
    {
        return self::find()->where(['post_id' => $this->post_id, 'parent_id' => $this->id])->all();
    }
}
