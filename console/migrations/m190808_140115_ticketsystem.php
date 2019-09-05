<?php

use yii\db\Migration;

/**
 * Class m190808_140115_ticketsystem
 */
class m190808_140115_ticketsystem extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'email' => $this->string()->notNull(),
            'password' => $this->string()->notNull(),
            'registered' => $this->dateTime(),
            'last_login' => $this->dateTime(),
            'is_admin' => $this->boolean()->defaultValue(false),

        ]);

        $this->createTable('ticket', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'status' => $this->boolean()->defaultValue(true),
            'user_id' => $this->integer(),
            'admin_id'=> $this->integer(),
            'modify_time'=> $this->dateTime(),//todo check timestamp with time zone
        ]);

        $this->createTable('comment', [
            'id' => $this->primaryKey(),
            'ticket_id' => $this->integer(),
            'user_id' => $this->integer(),
            'message' => $this->text(),
            'create_time'=> $this->dateTime(),

        ]);

        $this->createTable('image', [
            'id' => $this->primaryKey(),
            'ticket_id' => $this->integer(),
            'image_path' => $this->text(),

        ]);

        // add foreign key for table `ticket`column 'user_id'
        $this->addForeignKey(
            'fk-ticket_user_id-user_id',
            'ticket',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // add foreign key for table `ticket`column 'admin_id'
        $this->addForeignKey(
            'fk-ticket_admin_id-user_id',
            'ticket',
            'admin_id',
            'user',
            'id',
            'SET NULL',
            'CASCADE'
        );

        // add foreign key for table `comment`column 'ticket_id'
        $this->addForeignKey(
            'fk-comment_ticket_id-ticket_id',
            'comment',
            'ticket_id',
            'ticket',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // add foreign key for table `comment`column 'user_id'
        $this->addForeignKey(
            'fk-comment_user_id-user_id',
            'comment',
            'user_id',
            'user',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // add foreign key for table `image`column 'ticket_id'
        $this->addForeignKey(
            'fk-image_ticket_id-ticket_id',
            'image',
            'ticket_id',
            'ticket',
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('comment');
        $this->dropTable('ticket');
        $this->dropTable('user');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190808_140115_ticketsystem cannot be reverted.\n";

        return false;
    }
    */
}
