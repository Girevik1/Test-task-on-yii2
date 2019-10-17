<?php

namespace frontend\models\forms;

use yii\base\Model;
use frontend\models\Transaction;

/**
 * Description of TransactionForm
 *
 * @author admin
 */
class TransactionForm extends Model
{
    public $purpose;
    public $sum;
    public $user_id;

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'purpose' => 'Назначение',
            'sum' => 'Сумма списания или начисления',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['purpose', 'trim'],
            ['purpose', 'required'],
            ['purpose', 'string', 'max' => 255],

            ['sum', 'required'],
            ['sum', 'number'],
        ];
    }

    /**
     * @return bool
     * @throws \Throwable
     * @throws \frontend\components\NotEnoughMoneyException
     */
    public function save()
    {
        if ($this->validate()) {
            return Transaction::createTransactionForUser($this->purpose, $this->sum, $this->user_id);
        }
    }
}