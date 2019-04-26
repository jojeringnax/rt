<?php

namespace app\models;

use Yii;
use yii\helpers\Console;

/**
 * This is the model class for table "cars_data".
 *
 * @property string $car_id
 * @property string $driver
 * @property string $phone
 * @property string $start_time_plan
 * @property string $end_time_plan
 * @property double $work_time_plan
 * @property string $start_time_fact
 * @property double $work_time_fact
 * @property int $mileage
 * @property int $speed
 * @property double $fuel_norm
 * @property int $fuel_DUT
 * @property string $driver_mark
 * @property int $violations_count
 *
 * @property Car $car
 */
class CarsData extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cars_data';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['car_id'], 'required'],
            [['start_time_plan', 'end_time_plan'], 'safe'],
            [['work_time_plan', 'fuel_norm', 'work_time_fact'], 'number'],
            [['mileage', 'speed', 'fuel_DUT', 'violations_count'], 'integer'],
            [['car_id'], 'string', 'max' => 36],
            [['driver'], 'string', 'max' => 128],
            [['phone'], 'string', 'max' => 20],
            [['driver_mark'], 'string', 'max' => 16],
            [['car_id'], 'unique'],
            [['car_id'], 'exist', 'skipOnError' => true, 'targetClass' => Car::className(), 'targetAttribute' => ['car_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'car_id' => 'Car ID',
            'driver' => 'Driver',
            'phone' => 'Phone',
            'start_time_plan' => 'Start Time Plan',
            'end_time_plan' => 'End Time Plan',
            'work_time_plan' => 'Work Time Plan',
            'work_time_fact' => 'Work Time Fact',
            'mileage' => 'Mileage',
            'start_time_fact' => 'Start Time Fact',
            'speed' => 'Speed',
            'fuel_norm' => 'Fuel Norm',
            'fuel_DUT' => 'Fuel  Dut',
            'driver_mark' => 'Driver Mark',
            'violations_count' => 'Violations Count',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCar()
    {
        return $this->hasOne(Car::className(), ['id' => 'car_id']);
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
     * @return bool
     */
    public static function getAllCarsData()
    {
        $client = new \SoapClient(\Yii::$app->params['wsdl']);
        $cars = Car::find()->all();
        $i = 0;
        $count = count($cars);
        Console::startProgress(0, $count);
        foreach ($cars as $car) {
            $i++;
            try {
                $carsData = json_decode($client->GetCarsData(['CarsID' => $car->id])->return);
                $carsDataModel = self::getOrCreate($car->id);
                $carsDataModel->car_id = $car->id;
                $carsDataModel->driver = isset($carsData->Driver) ? $carsData->Driver : null;
                $carsDataModel->phone = isset($carsData->Phone) ? $carsData->Phone : null;
                $carsDataModel->start_time_plan = isset($carsData->StartTimePlan) ? $carsData->StartTimePlan : null;
                $carsDataModel->end_time_plan = isset($carsData->EndTimePlan) ? $carsData->EndTimePlan : null;
                $carsDataModel->work_time_plan = isset($carsData->EndTimePlan) ? $carsData->EndTimePlan : null;
                $carsDataModel->start_time_fact = isset($carsData->StartTimeFact) ? $carsData->StartTimeFact : null;
                $carsDataModel->work_time_fact = isset($carsData->WorkTimeFact) ? $carsData->WorkTimeFact : null;
                $carsDataModel->mileage = isset($carsData->Mileage) ? $carsData->Mileage : null;
                $carsDataModel->speed = isset($carsData->Speed) ? $carsData->Speed : null;
                $carsDataModel->fuel_norm = isset($carsData->FuelNorm) ? $carsData->FuelNorm : null;
                $carsDataModel->fuel_DUT = isset($carsData->FuelDUT) ? $carsData->FuelDUT : null;
                $carsDataModel->driver_mark = isset($carsData->DriverMark) ? $carsData->DriverMark : null;
                $carsDataModel->violations_count = isset($carsData->ViolationsCount) ? $carsData->ViolationsCount : null;
                $carsDataModel->save();
                Console::updateProgress($i, $count);
            } catch (\SoapFault $f) {
                Console::updateProgress($i, $count);
                continue;
            }
        }
        Console::endProgress();
        return true;
    }
}
