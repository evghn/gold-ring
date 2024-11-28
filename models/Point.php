<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "point".
 *
 * @property int $id
 * @property string $ttile
 * @property int $end_point
 *
 * @property Application[] $applications
 * @property Application[] $applications0
 * @property Edges[] $edges
 * @property Edges[] $edges0
 * @property Route[] $routes
 */
class Point extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'point';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ttile'], 'required'],
            [['end_point'], 'integer'],
            [['ttile'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ttile' => 'Ttile',
            'end_point' => 'End Point',
        ];
    }

    /**
     * Gets query for [[Applications]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplications()
    {
        return $this->hasMany(Application::class, ['point_start_id' => 'id']);
    }

    /**
     * Gets query for [[Applications0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getApplications0()
    {
        return $this->hasMany(Application::class, ['point_end_id' => 'id']);
    }

    /**
     * Gets query for [[Edges]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEdges()
    {
        return $this->hasMany(Edges::class, ['source_id' => 'id']);
    }

    /**
     * Gets query for [[Edges0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEdges0()
    {
        return $this->hasMany(Edges::class, ['target_id' => 'id']);
    }

    /**
     * Gets query for [[Routes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutes()
    {
        return $this->hasMany(Route::class, ['point_id' => 'id']);
    }
}
