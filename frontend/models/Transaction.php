<?php

namespace frontend\models;

use Yii;
use yii\base\ErrorException;
use frontend\components\NotEnoughMoneyException;

/**
 * This is the model class for table "transactions".
 *
 * @property int $id
 * @property int $user_id
 * @property string $purpose
 * @property string $diff_rub
 * @property string $diff_bonus
 * @property string $balance_rub
 * @property string $balance_bonus
 * @property string $date
 *
 * @property User $user
 */
class Transaction extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{transactions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'purpose', 'diff_rub', 'diff_bonus', 'balance_rub', 'balance_bonus', 'date'], 'required'],
            [['user_id'], 'integer'],
            [['purpose'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
            [['diff_rub', 'diff_bonus', 'balance_rub', 'balance_bonus'], 'number'],
            // format to 2 decimals
            [['diff_rub', 'diff_bonus', 'balance_rub', 'balance_bonus'], 'filter', 'filter' => function ($value) {
                return round($value, 2);
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'purpose' => 'Операция',
            'diff_rub' => 'Рублей',
            'diff_bonus' => 'Бонусов',
            'balance_rub' => 'Руб. баланс',
            'balance_bonus' => 'Бон. баланс',
            'date' => 'Дата',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * @param $purpose
     * @param $sum
     * @param $user_id
     * @return bool
     * @throws NotEnoughMoneyException
     * @throws \Throwable
     */
    public static function createTransactionForUser($purpose, $sum, $user_id)
    {
        return Yii::$app->db->transaction(function() use ($purpose, $sum, $user_id) {
            $user = User::findOne($user_id);

            if ($sum < 0) {
                // check if user has enough balance
                if (abs($sum) > ($user->balance_rub + $user->balance_bonus)) {
                    throw new NotEnoughMoneyException('Not enough money');
                }
            }

            $transaction = new Transaction();
            $transaction->purpose = $purpose;
            $transaction->user_id = $user->id;
            $transaction->date = date(DATE_ISO8601);
            $transaction->balance_rub = $user->balance_rub;
            $transaction->balance_bonus = $user->balance_bonus;

            if ($sum > 0) {
                $transaction->balance_rub += $sum;
                $transaction->balance_bonus += $sum * 0.1;
            }

            if ($sum < 0) {
                // try to write off from the bonus balance first
                // we can't write off more bonuses than user has
                // so take the minimum between sum to write off and the balance
                if (abs($sum) > $user->balance_bonus) {
                    $write_off_bonus = $user->balance_bonus;
                } else {
                    $write_off_bonus = abs($sum);
                }

                // write off rubles excluding wrote off bonuses
                $write_off_rub = abs($sum) - $write_off_bonus;

                $transaction->balance_rub -= $write_off_rub;
                $transaction->balance_bonus -= $write_off_bonus;
            }

            $transaction->diff_rub = $transaction->balance_rub - $user->balance_rub;
            $transaction->diff_bonus = $transaction->balance_bonus - $user->balance_bonus;

            if (!$transaction->validate() || !$transaction->save()) {
                throw new ErrorException('Can not save transaction');
            }

            $user->balance_rub = $transaction->balance_rub;
            $user->balance_bonus = $transaction->balance_bonus;

            if (!$user->validate() || !$user->save()) {
                throw new ErrorException('Can not save user');
            }

            return true;
        });
    }
}

