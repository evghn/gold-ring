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
    public ?string $inn = null;
    public ?string $password = null;
    public ?string $password_repeat = null;


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
            [[ 'title', 'address', 'kpp', 'rs', 'bank', 'bik',  'fio', 'phone', 'email', 'inn', 'password'], 'required'],
            [['title', 'address', 'bank', 'fio', 'email'], 'string', 'max' => 255],            
            [['rto'], 'match', 'pattern' => "/^[РТО]{3}\s([\d]{6})$/u", 'message' => 'Недействительный номер'],            
            [['kpp', 'bik'], 'match', 'pattern' => "/^[\d]{9}$/"],
            [['rs', 'kor'], 'match', 'pattern' => "/^[\d]{20}$/"],            
            
            [['fio'], 'match', 'pattern' => '/^[а-яё\s\-]+$/ui'],
            [['phone'], 'match', 'pattern' => "/^\+7(\s([\d]{3})){2}(\s([\d]{2})){2}$/"],
            ['email', 'email'],
            
            [['inn'], 'unique', 'targetClass' => User::class],
            [['inn'], 'match', 'pattern' => "/^[\d]{10}$/"],
            
            [['password'], 'string', 'max' => 255, 'min' => 6],
            [['password'], 'match', 'pattern' => '/^[a-z\d]+$/i'],
            [['password_repeat'], 'compare', 'compareAttribute' => 'password'],
            
            [['user_id'], 'integer'],            
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
            'title' => 'Полное наименование юридического лица',
            'address' => 'Адрес юридического лица',
            'rto' => 'Номер из реестра туроператоров',
            'kpp' => 'КПП',
            'inn' => 'ИНН',
            'rs' => 'Расчетный счет',
            'bank' => 'Наименование Банка',
            'bik' => 'Банковский идентификатор БИК ',
            'kor' => 'Корреспондентский счет',
            'fio' => 'Фамилия, имя, отчество',
            'phone' => 'Телефон',
            'email' => 'E-mail',
            'password' => 'Пароль',
            'password_repeat' => 'Повтор пароля',
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
