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
class Route extends \yii\db\ActiveRecord
{

    const SCENARIO_STEP1 = 'step1';
    const SCENARIO_STEP2 = 'step2';
    const SCENARIO_STEP3 = 'step3';

    public int $step = 1;
    public ?string $stop_point = null;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'route';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // [[ 'time_all', 'time_end', 'user_id'], 'required'],
            [['point_start_id', 'point_end_id', 'date_start', 'time_start'], 'required', 'on' => self::SCENARIO_STEP1],


            [['point_start_id', 'point_end_id', 'user_id', 'step'], 'integer'],
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
            'point_start_id' => 'Начальный пункт',
            'point_end_id' => 'Конечный пункт',
            'date_start' => 'Дата отправления',
            'time_start' => 'Время отправления',
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
     * Gets query for [[RouteItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRouteItems()
    {
        return $this->hasMany(RouteItem::class, ['route_id' => 'id']);
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
