<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "autocolumns".
 *
 * @property string $id
 * @property string $company_id
 * @property string $spot_id
 * @property string $description
 * @property string $address
 * @property double $x_pos
 * @property double $y_pos
 *
 * @property Companies $company
 * @property Spots $spot
 */
class Autocolumn extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'autocolumns';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['address'], 'string'],
            [['x_pos', 'y_pos'], 'number'],
            [['id', 'company_id', 'spot_id'], 'string', 'max' => 36],
            [['description'], 'string', 'max' => 512],
            [['id'], 'unique'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Companies::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['spot_id'], 'exist', 'skipOnError' => true, 'targetClass' => Spots::className(), 'targetAttribute' => ['spot_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'company_id' => 'Company ID',
            'spot_id' => 'Spot ID',
            'description' => 'Description',
            'address' => 'Address',
            'x_pos' => 'X Pos',
            'y_pos' => 'Y Pos',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCompany()
    {
        return $this->hasOne(Companies::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpot()
    {
        return $this->hasOne(Spots::className(), ['id' => 'spot_id']);
    }
}
