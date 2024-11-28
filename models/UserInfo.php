<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_info".
 *
 * @property int $id
 * @property int $user_id
 * @property string $title
 * @property string $address
 * @property string $rto
 * @property string $kpp
 * @property string $rs
 * @property string $bank
 * @property string $bik
 * @property string $kor
 * @property string $fio
 * @property string $phone
 * @property string $email
 *
 * @property User $user
 */
class UserInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'title', 'address', 'rto', 'kpp', 'rs', 'bank', 'bik', 'kor', 'fio', 'phone', 'email'], 'required'],
            [['id', 'user_id'], 'integer'],
            [['title', 'address', 'bank', 'fio', 'email'], 'string', 'max' => 255],
            [['rto'], 'string', 'max' => 10],
            [['kpp', 'bik'], 'string', 'max' => 9],
            [['rs', 'kor'], 'string', 'max' => 20],
            [['phone'], 'string', 'max' => 16],
            [['id'], 'unique'],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
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
            'title' => 'Title',
            'address' => 'Address',
            'rto' => 'Rto',
            'kpp' => 'Kpp',
            'rs' => 'Rs',
            'bank' => 'Bank',
            'bik' => 'Bik',
            'kor' => 'Kor',
            'fio' => 'Fio',
            'phone' => 'Phone',
            'email' => 'Email',
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
