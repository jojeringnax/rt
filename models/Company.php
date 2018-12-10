<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "companies".
 *
 * @property string $id
 * @property string $name
 *
 * @property Autocolumn[] $autocolumns
 * @property Organization[] $organizations
 * @property Spot[] $spots
 */
class Company extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'companies';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['id'], 'string', 'max' => 36],
            [['name'], 'string', 'max' => 256],
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
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutocolumns()
    {
        return $this->hasMany(Autocolumn::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizations()
    {
        return $this->hasMany(Organization::className(), ['company_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpots()
    {
        return $this->hasMany(Spot::className(), ['company_id' => 'id']);
    }


    public static function getBoundsOfElement($el, $xMin, $xMax, $yMin, $yMax)
    {
        if($el->x_pos < $xMin) {
            $xMin = $el->x_pos;
        }
        if($el->x_pos > $xMax) {
            $xMax = $el->x_pos;
        }
        if($el->y_pos < $yMin) {
            $yMin = $el->y_pos;
        }
        if($el->y_pos > $yMax) {
            $yMax = $el->y_pos;
        }
        return array(
            'xMax' => $xMax,
            'yMax' => $yMax,
            'xMin' => $xMin,
            'yMin' => $yMin
        );
    }


}
