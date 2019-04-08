<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bad_spots".
 *
 * @property string $id
 * @property string $company_id
 * @property string $organization_id
 * @property string $autocolumn_id
 * @property string $description
 * @property string $name
 * @property string $town
 * @property string $address
 * @property double $x_pos
 * @property double $y_pos
 *
 * @property Company $company
 * @property Autocolumn $autocolumn
 * @property Organization $organization
 */
class BadSpot extends \yii\db\ActiveRecord
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
    public $carsTotal;

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
        return 'bad_spots';
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
            [['name'], 'string', 'max' => 128],
            [['town'], 'string', 'max' => 32],
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
            'name' => 'Name',
            'town' => 'Town',
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

    /**
     * @return array
     */
    public function getCarsNumberWithStatuses()
    {
        $result = [
            'total' => 0,
            'types' => [0,0,0,0],
            'statuses_count' => [
                'G' => 0,
                'R' => 0,
                'TO' => 0
            ],
            'inline' => 0
        ];
        $cars = $this->getCars()->all();
        if ($cars === null) {
            return $result;
        }
        if (!is_array($cars)) {
            $result['total'] = 1;
            $result['type'][$cars->type] = 1;
            $result['statuses_count'][$cars->status] = 1;
            return $result;
        }
        $result['total'] += count($cars);
        foreach ($cars as $car) {
            if ($car->type !== null)
                $result['types'][$car->type]++;
            if ($car->status !== null)
                $result['statuses_count'][$car->status]++;
            if ($car->inline)
                $result['inline']++;
        }
        return $result;
    }


    /**
     * @return int|string
     */
    public function getTotalCars()
    {
        return Car::find()->where(['spot_id' => $this->id])->andWhere(['!=', 'x_pos', 0])->count();
    }
}
