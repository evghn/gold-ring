<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "application".
 *
 * @property int $id
 * @property int $point_start_id
 * @property int $point_end_id
 * @property string $date_start
 * @property string $time_start
 * @property string $time_all
 * @property string $time_end
 * @property int $user_id
 * @property string $created_at
 * @property string|null $updated_at
 *
 * @property Point $pointEnd
 * @property Point $pointStart
 * @property Route[] $routes
 * @property User $user
 */
class Application extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'application';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['point_start_id', 'point_end_id', 'date_start', 'time_start', 'time_all', 'time_end', 'user_id'], 'required'],
            [['point_start_id', 'point_end_id', 'user_id'], 'integer'],
            [['date_start', 'time_start', 'time_all', 'time_end', 'created_at', 'updated_at'], 'safe'],
            [['point_start_id'], 'exist', 'skipOnError' => true, 'targetClass' => Point::class, 'targetAttribute' => ['point_start_id' => 'id']],
            [['point_end_id'], 'exist', 'skipOnError' => true, 'targetClass' => Point::class, 'targetAttribute' => ['point_end_id' => 'id']],
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
            'point_start_id' => 'Point Start ID',
            'point_end_id' => 'Point End ID',
            'date_start' => 'Date Start',
            'time_start' => 'Time Start',
            'time_all' => 'Time All',
            'time_end' => 'Time End',
            'user_id' => 'User ID',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * Gets query for [[PointEnd]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPointEnd()
    {
        return $this->hasOne(Point::class, ['id' => 'point_end_id']);
    }

    /**
     * Gets query for [[PointStart]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPointStart()
    {
        return $this->hasOne(Point::class, ['id' => 'point_start_id']);
    }

    /**
     * Gets query for [[Routes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes()
    {
        return $this->hasMany(Route::class, ['application_id' => 'id']);
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
