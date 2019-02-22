<?php

namespace app\models;

use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "autocolumns".
 *
 * @property string $id
 * @property string $company_id
 * @property string $organization_id
 * @property string $description
 * @property string $address
 * @property string $town
 * @property string $name
 * @property double $x_pos
 * @property double $y_pos
 *
 * @property Company $company
 * @property Organization $organization
 * @property Spot[] $spots
 */
class Autocolumn extends \yii\db\ActiveRecord
{
    /**
     * @var string
     */
    public $bounds;

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
            [['id', 'company_id', 'organization_id'], 'string', 'max' => 36],
            [['description'], 'string', 'max' => 512],
            [['id'], 'unique'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['organization_id'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['organization_id' => 'id']],
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
            'organization_id' => 'Organization ID',
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
        return $this->hasOne(Company::className(), ['id' => 'company_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id' => 'organization_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSpots()
    {
        return $this->hasMany(Spot::className(), ['autocolumn_id' => 'id']);
    }

    /**
     * @return string
     */
    public function getIdWithoutNumbers()
    {
        $s = array('/0/', '/1/', '/2/', '/3/', '/4/', '/5/', '/6/', '/7/', '/8/', '/9/', '/-/');
        $a = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', '');
        return preg_replace($s, $a, $this->id);
    }

    /**
     * @return int|string
     */
    public function getNumberOfTerminals()
    {
        $spots = Spot::find()->where(['autocolumn_id' => $this->id])->select('id')->column();
        return Car::find()->where(['spot_id' => $spots, 'terminal' => 1])->andWhere(['not', ['x_pos' => null]])->count();
    }

    /**
     * @return Statistic|null
     */
    public function getStatistic()
    {
        $statistics = Statistic::find()->where(['autocolumn_id' => $this->id])->all();
        if ($statistics == null) return null;
        $resultStatistic = new Statistic();
        foreach ($statistics as $statistic) {
            foreach ($statistic->attributes() as $attribute) {
                if ($attribute == 'id' || $attribute == 'spot_id') {
                    continue;
                }
                if ($attribute == 'autocolumn_id') {
                    $resultStatistic->autocolumn_id = $statistic->autocolumn_id;
                    continue;
                }
                try {
                    $resultStatistic->$attribute += $statistic->$attribute;
                } catch (\Exception $e) {
                    echo $attribute;
                    echo $e->getMessage();
                }
            }
        }
        return $resultStatistic;
    }


    /**
     * @param $id
     * @return self|null|static
     */
    public static function getOrCreate($id)
    {
        $model = self::findOne($id);
        return $model === null ? new self() : $model;
    }

    /**
     * @return array|self[]
     */
    public static function getActives()
    {
        return self::find()->where(['!=', 'x_pos', 0])->all();
    }


    public static function getAutocolumnsFromSoapAndSaveInDB()
    {
        $divis = new Division();
        $autocolumns = $divis->getAutocolumns();
        foreach ($autocolumns as $autocolumn) {
            try {
                $autocolumnMod = self::getOrCreate($autocolumn->ID);
                $autocolumnMod->id = $autocolumn->ID;
                $haveParams = isset(Yii::$app->params['autocolumns'][$autocolumn->ID]);
                $autocolumnMod->town = $haveParams ? Yii::$app->params['autocolumns'][$autocolumn->ID][1] : null;
                $autocolumnMod->name = $haveParams ? Yii::$app->params['autocolumns'][$autocolumn->ID][0] : null;
                $autocolumnMod->company_id = '762b8f6f-1a46-11e5-be74-00155dc6002b';
                $autocolumnMod->organization_id = $autocolumn->FirmsID;
                $autocolumnMod->description = $autocolumn->Description;
                $autocolumnMod->address = $autocolumn->Address;
                $autocolumnMod->x_pos = $autocolumn->XPos;
                $autocolumnMod->y_pos = $autocolumn->YPos;
                $autocolumnMod->save();
            } catch (Exception $e) {
                continue;
            }
        }
    }


    /**
     * @param $id
     * @return bool
     */
    public static function isExist($id)
    {
        return (boolean)self::find($id)->one();
    }

}
