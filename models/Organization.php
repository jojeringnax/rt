<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\Exception;

/**
 * This is the model class for table "organizations".
 *
 * @property string $id
 * @property string $company_id
 * @property string $description
 * @property string $address
 * @property double $x_pos
 * @property double $y_pos
 *
 * @property Company $company
 * @property Autocolumn[] $autocolumns
 * @property BadSpot[] $badSpots
 * @property Spot[] $spots
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * @var integer
     */
    public $carsTotal;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'organizations';
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
            [['id', 'company_id'], 'string', 'max' => 36],
            [['description'], 'string', 'max' => 512],
            [['id'], 'unique'],
            [['company_id'], 'exist', 'skipOnError' => true, 'targetClass' => Company::className(), 'targetAttribute' => ['company_id' => 'id']],
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
    public function getSpots()
    {
        return $this->hasMany(Spot::className(), ['organization_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAutocolumns()
    {
        return $this->hasMany(Autocolumn::class, ['organization_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBadSpots()
    {
        return $this->hasMany(BadSpot::className(), ['organization_id' => 'id']);
    }

    /**
     * @param $id
     * @return self|null|static
     */
    public static function getOrCreate($id)
    {
        $model = self::findOne($id);
        return $model === null ? new self : $model;
    }

    /**
     * @return array|self[]
     */
    public static function getActives()
    {
        return self::find()->where(['!=', 'x_pos', 0])->all();
    }

    /**
     * @return string
     */
    public static function getMaxAndMinCoordinatesForAPI()
    {
        $xPosMin = self::find()->min('x_pos');
        $yPosMin = self::find()->min('y_pos');
        $xPosMax = self::find()->max('x_pos');
        $yPosMax = self::find()->max('y_pos') + 3.5;
        return "[[$xPosMin, $yPosMin],[$xPosMax, $yPosMax]]";
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
     * @return bool|string
     */
    public function getTown()
    {
        $result = strripos($this->description, 'г.');
        $length = -(strlen($this->description) - $result);
        return substr($this->description, $length);
    }


    public static function getOrganizationsFromSoapAndSaveInDB()
    {
        $client = new \SoapClient('http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl');
        $organizations = json_decode($client->getOrganization()->return);
        foreach ($organizations as $organization) {
            $organizationMod = self::getOrCreate($organization->ID);
            $organizationMod->id = $organization->ID;
            $organizationMod->company_id = '762b8f6f-1a46-11e5-be74-00155dc6002b';
            $organizationMod->description = $organization->Description;
            $organizationMod->address = $organization->Address;
            $organizationMod->x_pos = $organization->XPos;
            $organizationMod->y_pos = $organization->YPos;
            $organizationMod->save();
        }
        return true;
    }


    /**
     * @return int|string
     */
    public function getNumberOfTerminals()
    {
        $spots = $this->getSpotIds();
        return Car::find()->where(['spot_id' => $spots, 'terminal' => 1])->andWhere(['not', ['x_pos' => null]])->count();
    }

    /**
     * @return Statistic|null
     */
    public function getStatistic()
    {
        $spots = $this->getSpotIds();
        if ($spots == null) {
            return null;
        }
        $statistics = Statistic::find()->where(['spot_id' => $spots])->all();
        if ($statistics == null) {
            return null;
        }
        $resultStatistic = new Statistic();
        foreach ($statistics as $statistic) {
            foreach ($statistic->attributes() as $attribute) {
                if ($attribute == 'id' || $attribute == 'spot_id' || $attribute == 'autocolumn_id') {
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
     * @return array
     */
    public function getSpotIds()
    {
        return Spot::find()->where(['organization_id' => $this->id])->select('id')->column();
    }

    /**
     * @return array|null
     */
    public function getBounds()
    {
        $badSpots = $this->badSpots;
        $bounds = Division::getBoundsAsArray($this->autocolumns);
        foreach ($badSpots as $badSpot) {
            if ($badSpot->x_pos > $bounds['x_max'])
                $bound['x_max'] = $badSpot->x_pos;
            if ($badSpot->x_pos < $bounds['x_min'])
                $bound['x_min'] = $badSpot->x_pos;
            if ($badSpot->y_pos > $bounds['y_max'])
                $bound['y_max'] = $badSpot->y_pos;
            if ($badSpot->y_pos < $bounds['y_min'])
                $bound['y_min'] = $badSpot->y_pos;
        }
        return $bounds;
    }


    /**
     * @return int|string
     */
    public function getTotalCars()
    {
        /**
         * @var $autocolumns Autocolumn[]
         */
        $autocolumns = $this->getAutocolumns()->where(['!=', 'x_pos', 0])->all();
        $badSpots = $this->getBadSpots()->where(['!=', 'x_pos', 0])->all();
        $result = 0;
        foreach ($autocolumns as $autocolumn) {
            $spots = $autocolumn->getSpots()->where(['!=', 'x_pos', 0])->all();
            if($spots !== null) {
                foreach ($spots as $spot) {
                    $result += Car::find()->where(['spot_id' => $spot->id])->andWhere(['!=', 'x_pos', 0])->count();
                }
            }
        }
        foreach ($badSpots as $badSpot) {
            $result += Car::find()->where(['spot_id' => $badSpot->id])->andWhere(['!=', 'x_pos', 0])->count();
        }
        return $result;
    }

    /**
     * @return array
     */
    public function getCarsNumberWithStatuses()
    {
        $spots = $this->getSpots()->where(['!=', 'x_pos', 0])->all();
        $badSpots = $this->badSpots;
        $result = [
            'total' => 0,
            'types' => [0, 0, 0, 0],
            'statuses_count' => [
                'G' => 0,
                'R' => 0,
                'TO' => 0
            ],
            'inline' => 0
        ];
        foreach ($spots as $spot) {
            $spotRes = $spot->getCarsNumberWithStatuses();
            $result['total'] += $spotRes['total'];
            $result['inline'] += $spotRes['inline'];
            foreach ($result['types'] as $key => $value) {
                $result['types'][$key] += $spotRes['types'][$key];
            }
            foreach ($result['statuses_count'] as $key => $value) {
                $result['statuses_count'][$key] += $spotRes['statuses_count'][$key];
            }
        }

        foreach ($badSpots as $badSpot) {
            $spotRes = $badSpot->getCarsNumberWithStatuses();
            $result['total'] += $spotRes['total'];
            $result['inline'] += $spotRes['inline'];
            foreach ($result['types'] as $key => $value) {
                $result['types'][$key] += $spotRes['types'][$key];
            }
            foreach ($result['statuses_count'] as $key => $value) {
                $result['statuses_count'][$key] += $spotRes['statuses_count'][$key];
            }
        }
        return $result;
    }
}
