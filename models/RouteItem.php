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
            [['route_id', 'point_id'], 'required'],
            [['route_id', 'point_id', 'time_route'], 'integer'],
            [['pause'], 'safe'],
            [['route_id'], 'exist', 'skipOnError' => true, 'targetClass' => Route::class, 'targetAttribute' => ['route_id' => 'id']],
            [['point_id'], 'exist', 'skipOnError' => true, 'targetClass' => Point::class, 'targetAttribute' => ['point_id' => 'id']],
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
            'pause' => 'Pause',
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
