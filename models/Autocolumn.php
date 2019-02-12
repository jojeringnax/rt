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
        $s = array('/0/','/1/','/2/','/3/','/4/','/5/','/6/','/7/','/8/','/9/', '/-/');
        $a = array('a','b','c','d','e','f','g','h','i','j','');
        return preg_replace($s, $a, $this->id);
    }

    /**
     * @return Statistic|null
     */
    public function getStatistic()
    {
        return Statistic::findOne(['autocolumn_id' => $this->id]);
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
                $autocolumnMod->company_id = '762b8f6f-1a46-11e5-be74-00155dc6002b';
                $autocolumnMod->organization_id = $autocolumn->FirmsID;
                $autocolumnMod->description = $autocolumn->Description;
                $autocolumnMod->address = $autocolumn->Address;
                $autocolumnMod->x_pos = $autocolumn->XPos;
                $autocolumnMod->y_pos = $autocolumn->YPos;
                $autocolumnMod->save();
            } catch (Exception $e) {
                $log = new Log();
                $log->message = $e->getTraceAsString();
                $log->created_at = date('Y-m-d H:i:s');
                $log->save();
            }
        }
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
