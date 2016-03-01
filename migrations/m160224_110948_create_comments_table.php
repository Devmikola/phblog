<?php

use yii\db\Schema;
use yii\db\Migration;

class m160224_110948_create_comments_table extends Migration
{
    public function up()
    {
        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'id_in_post' => $this->integer(),
            'parent_id' => $this->integer(),
            'user_id' => $this->integer(),
            'post_id' => $this->integer(),
            'content' => $this->text(4000),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-comment-user_id', 'comment', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-comment-post_id', 'comment', 'post_id', 'post', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk-comment-parent_id', 'comment', 'parent_id', 'comment', 'id', 'CASCADE', 'CASCADE');

    }

    public function down()
    {
        $this->dropTable('comment');
    }

}
