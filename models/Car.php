<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cars".
 *
 * @property string $id
 * @property string $number
 * @property int $type
 * @property string $model
 * @property string $description
 * @property int $year
 * @property double $x_pos
 * @property double $y_pos
 */
class Car extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cars';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['type', 'year'], 'integer'],
            [['x_pos', 'y_pos'], 'number'],
            [['id'], 'string', 'max' => 36],
            [['number'], 'string', 'max' => 15],
            [['model'], 'string', 'max' => 32],
            [['description'], 'string', 'max' => 512],
            [['id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'number' => 'Number',
            'type' => 'Type',
            'model' => 'Model',
            'description' => 'Description',
            'year' => 'Year',
            'x_pos' => 'X Pos',
            'y_pos' => 'Y Pos',
        ];
    }
}
