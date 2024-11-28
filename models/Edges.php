<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "edges".
 *
 * @property int $id
 * @property int $source_id
 * @property int $target_id
 * @property string $time
 *
 * @property Point $source
 * @property Point $target
 */
class Edges extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'edges';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['source_id', 'target_id', 'time'], 'required'],
            [['source_id', 'target_id'], 'integer'],
            [['time'], 'safe'],
            [['source_id'], 'exist', 'skipOnError' => true, 'targetClass' => Point::class, 'targetAttribute' => ['source_id' => 'id']],
            [['target_id'], 'exist', 'skipOnError' => true, 'targetClass' => Point::class, 'targetAttribute' => ['target_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'source_id' => 'Source ID',
            'target_id' => 'Target ID',
            'time' => 'Time',
        ];
    }

    /**
     * Gets query for [[Source]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSource()
    {
        return $this->hasOne(Point::class, ['id' => 'source_id']);
    }

    /**
     * Gets query for [[Target]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTarget()
    {
        return $this->hasOne(Point::class, ['id' => 'target_id']);
    }
}
