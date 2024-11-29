<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "point".
 *
 * @property int $id
 * @property string $title
 * @property int $end_point
 *
 * @property Route[] $Routes
 * @property Route[] $Routes0
 * @property Edges[] $edges
 * @property Edges[] $edges0
 * @property RouteItem[] $routeItems
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
            [['title'], 'required'],
            [['end_point'], 'integer'],
            [['title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'end_point' => 'End Point',
        ];
    }

    /**
     * Gets query for [[Routs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRouts()
    {
        return $this->hasMany(Route::class, ['point_start_id' => 'id']);
    }

    /**
     * Gets query for [[Route0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoute0()
    {
        return $this->hasMany(Route::class, ['point_end_id' => 'id']);
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
     * Gets query for [[RoutItems]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRoutItems()
    {
        return $this->hasMany(RouteItem::class, ['point_id' => 'id']);
    }


    public static function getStartPoints()
    {
        return self::find()
                   ->select('title')
                   ->where(['end_point' => 0])
                   ->indexBy('id')
                   ->column()
                   ;
    }

    public static function getEndPoints($id)
    {
        return self::find()                   
                   ->where(['!=', 'id',  $id])
                   ->all()
                   ;
    }
    

}
