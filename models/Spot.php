<?php

namespace app\models;

use DeepCopy\f001\A;
use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "spots".
 *
 * @property string $id
 * @property string $company_id
 * @property string $organization_id
 * @property string $autocolumn_id
 * @property string $description
 * @property string $address
 * @property string $town
 * @property string $name
 * @property double $x_pos
 * @property double $y_pos
 *
 * @property Company $company
 * @property Autocolumn $autocolumn
 * @property Organization $organization
 */
class Spot extends \yii\db\ActiveRecord
{
    /**
     * @var string
     */
    public $bounds;

    /**
     * @var integer
     */
    public $carsNumber;

    /**
     * @var integer
     */
    public $carsStatuses;

    /**
     * @var array
     */
    public $carsTypes;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'spots';
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
            [['id', 'company_id', 'organization_id', 'autocolumn_id'], 'string', 'max' => 36],
            [['description'], 'string', 'max' => 512],
            [['id'], 'unique'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
            [['autocolumn_id'], 'exist', 'skipOnError' => true, 'targetClass' => Autocolumn::className(), 'targetAttribute' => ['autocolumn_id' => 'id']],
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
            'autocolumn_id' => 'Autocolumn ID',
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
    public function getAutocolumn()
    {
        return $this->hasOne(Autocolumn::className(), ['id' => 'autocolumn_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::className(), ['id' => 'organization_id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getCars()
    {
        return Car::find()->where(['spot_id' => $this->id])->andWhere(['!=','x_pos',0]);
    }

    /**
     * @return int|string
     */
    public function getNumberOfTerminals()
    {
        return Car::find()->where(['spot_id' => $this->id, 'terminal' => 1])->andWhere(['not', ['x_pos' => null]])->count();
    }

    /**
     * @return Statistic|null
     */
    public function getStatistic()
    {
        return Statistic::findOne(['spot_id' => $this->id]);
    }

    /**
     * @return string
     */
    public function getIdWithoutNumbers()
    {
        $s = array('/0/','/1/','/2/','/3/','/4/','/5/','/6/','/7/','/8/','/9/', '/-/');
        $a = array('a','b','c','d','e','f','g','h','i','j','');
        return preg_replace($s, $a, $this->id);
    }

    /**
     * @return array|self[]
     */
    public static function getActives()
    {
        return self::find()->where(['!=', 'x_pos', 0])->all();
    }

    /**
     * @return array|null
     */
    public static function getIDs()
    {
        foreach (self::find()->all() as $spot) {
            $resultArray[] = $spot->id;
        }
        return isset($resultArray) ? $resultArray : null;
    }

    public static function getSpotsFromSoapAndSaveInDB()
    {
        $divis = new Division();
        $spots = $divis->getSpots();
        foreach ($spots as $spot) {
            $spotMod = self::getOrCreate($spot->ID);
            $spotMod->id = $spot->ID;
            $spotMod->company_id = '762b8f6f-1a46-11e5-be74-00155dc6002b';
            $spotMod->organization_id = $spot->FirmsID;
            $spotMod->autocolumn_id = $spot->ParentID;
            $spotMod->description = $spot->Description;
            $haveParams = isset(Yii::$app->params['spots'][$spot->ID]);
            $spotMod->town = $haveParams ? Yii::$app->params['spots'][$spot->ID][1] : null;
            $spotMod->name = $haveParams ? Yii::$app->params['spots'][$spot->ID][0] : null;
            $spotMod->address = $spot->Address;
            $spotMod->x_pos = $spot->XPos;
            $spotMod->y_pos = $spot->YPos;
            $spotMod->save();
        }
        return true;
    }


    public static function fixBadSpots()
    {
        $badSpots = self::find()->where(['!=', 'x_pos', 0])->andWhere(['autocolumn_id' => null])->andWhere(['!=', 'organization_id', 0])->all();
        foreach ($badSpots as $badSpot) {
            $autocolumn = new Autocolumn();
            $autocolumn->id = $badSpot->id;
            $autocolumn->organization_id = $badSpot->organization_id;
            $autocolumn->company_id = $badSpot->company_id;
            $autocolumn->description = $badSpot->description;
            $autocolumn->name = $badSpot->name;
            $autocolumn->town = $badSpot->town;
            $autocolumn->address =  $badSpot->address;
            $autocolumn->x_pos = $badSpot->x_pos;
            $autocolumn->y_pos = $badSpot->y_pos;
            $autocolumn->save();
            $badSpot->autocolumn_id = $badSpot->id;
            $badSpot->save();
        }
        return true;
    }

    /**
     * @param $id
     * @return bool
     */
    public static function isExist($id)
    {
        return (boolean) self::find($id)->one();
    }


}
