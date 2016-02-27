<?php

use yii\db\Schema;
use yii\db\Migration;

class m160220_152933_create_post extends Migration
{
    public function up()
    {
        $this->createTable('post', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'title' => $this->string(240)->notNull(),
            'content' => $this->text(4000)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ]);

        $this->addForeignKey('fk-post-user_id', 'post', 'user_id', 'user', 'id', 'CASCADE', 'CASCADE');
    }

    public function down()
    {
        $this->dropTable('post');
    }

}
