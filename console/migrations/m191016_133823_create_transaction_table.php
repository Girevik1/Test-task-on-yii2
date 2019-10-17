<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%transaction}}`.
 */
class m191016_133823_create_transaction_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%transactions}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(10)->notNull(),
            'purpose' => $this->string(255)->notNull(),
            'diff_rub' => $this->decimal(12, 2)->notNull(),
            'diff_bonus' => $this->decimal(12, 2)->notNull(),
            'balance_rub' => $this->decimal(12, 2)->notNull(),
            'balance_bonus' => $this->decimal(12, 2)->notNull(),
            'date' => $this->dateTime()->notNull()
        ]);

        $this->addForeignKey(
            'user_id',
            'transactions',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%transactions}}');
    }
}
