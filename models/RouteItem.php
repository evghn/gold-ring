<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "route".
 *
 * @property int $id
 * @property int $route_id
 * @property int $point_id
 * @property string|null $pause
 * @property int $time_route
 *
 * @property Application $application
 * @property Point $point
 */
class RouteItem extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'create';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'route_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['route_id', 'point_id'], 'required', 'on' => self::SCENARIO_CREATE],
            [['route_id', 'point_id', 'time_visit', 'time_out', 'time_pause_sec', 'time_route_sec'], 'integer'],
            [['time_route',  'time_pause'], 'safe'],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::class, 'targetAttribute' => ['route_id' => 'id']],
            [['point_id'], 'exist', 'skipOnError' => true, 'targetClass' => Point::class, 'targetAttribute' => ['point_id' => 'id']],
            [['time_pause'], 'time', 'min' => '2:00', 'max' => '6:00', 'format' => 'php:H:i'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'route_id' => 'Route ID',
            'point_id' => 'Point ID',
            'time_pause' => 'Время остановки',
            'time_route' => 'Time Route',
        ];
    }

    /**
     * Gets query for [[Route]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoute()
    {
        return $this->hasOne(Route::class, ['id' => 'route_id']);
    }

    /**
     * Gets query for [[Point]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoint()
    {
        return $this->hasOne(Point::class, ['id' => 'point_id']);
    }


    
}
