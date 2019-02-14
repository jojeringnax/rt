<?php
/**
 * Created by PhpStorm.
 * User: Броненосец
 * Date: 12.02.2019
 * Time: 15:20
 */

namespace app\models;


use Codeception\Util\Soap;
use yii\db\ActiveRecord;
use yii\helpers\Console;


/**
 * Class Statistic
 * @package app\models\
 *
 * @property string $spot_id
 * @property string $autocolumn_id
 * @property integer $applications_total
 * @property integer $applications_executed
 * @property integer $applications_canceled
 * @property integer $applications_sub
 * @property integer $applications_ac
 * @property integer $applications_mp
 * @property integer $waybills_total
 * @property integer $waybills_processed
 * @property integer $accidents_total
 * @property integer $accidents_guilty
 * @property float $time
 * @property float $fuel
 */
class Statistic extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'statistics';
    }

    public $attributes = [];


    public static function getAllStats()
    {
        $client = new \SoapClient('http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl');
        self::getApplications($client);
        self::getWaybills($client);
        self::getAccidents($client);
        self::getTMCH($client);
        self::setAutocolumnsIds();
    }

    public static function setAutocolumnsIds()
    {
        $statistics = self::find()->all();
        $count = count($statistics);
        Console::startProgress(0, $count);
        $i = 0;
        foreach ($statistics as $statistic) {
            $i++;
            $spot = Spot::findOne(['id' => $statistic->spot_id]);
            if ($spot != null) {
                $statistic->autocolumn_id = $spot->autocolumn_id;
                $statistic->save();
            }
            Console::updateProgress($i, $count);
        }
        Console::endProgress();
    }

    public static function getApplications(\SoapClient $client)
    {
        $applications = json_decode($client->GetRequests()->return);
        try {
            $count = count($applications);
        } catch (\Exception $e) {
            $count = 0;
        };
        echo 'Only applications processing';
        Console::startProgress(0,$count);
        $i = 0;
        foreach($applications as $application) {
            $i++;
            $statistic = self::getOrCreate($application->DivisionID);
            if (Spot::isExist($application->DivisionID)) {
                $statistic = self::findOne(['spot_id' => $application->DivisionID]);
                if ($statistic == null) {
                    $statistic = new self();
                    $statistic->spot_id = $application->DivisionID;
                }
            } else if (Autocolumn::isExist($application->DivisionID)) {
                $statistic = self::findOne(['autocolumn_id' => $application->DivisionID]);
                if ($statistic == null) {
                    $statistic = new self();
                    $statistic->autocolumn_id = $application->DivisionID;
                }
            }
            $statistic->applications_total = isset($application->CountAll) ? $application->CountAll : 0;
            $statistic->applications_executed = isset($application->CountPlan) ? $application->CountPlan : 0;
            $statistic->applications_canceled = isset($application->CountCancel) ? $application->CountCancel : 0;
            $statistic->applications_sub = isset($application->CountSub) ? $application->CountSub : 0;
            $statistic->applications_ac = isset($application->countAC) ? $application->countAC : 0;
            $statistic->applications_mp = isset($application->countMP) ? $application->countMP : 0;
            $statistic->save();
            Console::updateProgress($i, $count);
        }
        Console::endProgress();
    }

    public static function getAccidents(\SoapClient $client)
    {
        $accidents = json_decode($client->GetDTP()->return);
        try {
            $count = count($accidents);
        } catch (\Exception $e) {
            $count = 0;
        };
        echo 'Only accidents processing';
        Console::startProgress(0,$count);
        $i = 0;
        foreach($accidents as $accident) {
            $i++;
            $statistic = self::getOrCreate($accident->DivisionID);
            if (Spot::isExist($accident->DivisionID)) {
                $statistic = self::findOne(['spot_id' => $accident->DivisionID]);
                if ($statistic == null) {
                    $statistic = new self();
                    $statistic->spot_id = $accident->DivisionID;
                }
            } else if (Autocolumn::isExist($accident->DivisionID)) {
                $statistic = self::findOne(['autocolumn_id' => $accident->DivisionID]);
                if ($statistic == null) {
                    $statistic = new self();
                    $statistic->autocolumn_id = $accident->DivisionID;
                }
            }
            $statistic->accidents_total = isset($accident->CountAll) ? $accident->CountAll : 0;
            $statistic->accidents_guilty = isset($accident->CountRT) ? $accident->CountRT : 0;
            $statistic->save();
            Console::updateProgress($i, $count);
        }
        Console::endProgress();
    }

    public static function getWaybills(\SoapClient $client)
    {
        $waybills = json_decode($client->GetWayBillProcessing()->return);
        try {
            $count = count($waybills);
        } catch (\Exception $e) {
            $count = 0;
        };
        echo 'Only waybills processing';
        Console::startProgress(0,$count);
        $i = 0;
        foreach($waybills as $waybill) {
            $i++;
            $statistic = self::getOrCreate($waybill->DivisionID);
            if (Spot::isExist($waybill->DivisionID)) {
                $statistic = self::findOne(['spot_id' => $waybill->DivisionID]);
                if ($statistic == null) {
                    $statistic = new self();
                    $statistic->spot_id = $waybill->DivisionID;
                }
            } else if (Autocolumn::isExist($waybill->DivisionID)) {
                $statistic = self::findOne(['autocolumn_id' => $waybill->DivisionID]);
                if ($statistic == null) {
                    $statistic = new self();
                    $statistic->autocolumn_id = $waybill->DivisionID;
                }
            }
            $statistic->waybills_total = isset($waybill->CountAll) ? $waybill->CountAll : 0;
            $statistic->waybills_processed = isset($waybill->CountProcessed) ? $waybill->CountProcessed : 0;
            $statistic->save();
            Console::updateProgress($i, $count);
        }
        Console::endProgress();
    }

    public static function getTMCH(\SoapClient $client)
    {
        $tmchs = json_decode($client->getTMCH()->return);
        try {
            $count = count($tmchs);
        } catch (\Exception $e) {
            $count = 0;
        };
        Console::startProgress(0,$count);
        $i = 0;
        foreach($tmchs as $tmch) {
            $i++;
            $statistic = self::getOrCreate($tmch->DivisionID);
            if (Spot::isExist($tmch->DivisionID)) {
                $statistic = self::findOne(['spot_id' => $tmch->DivisionID]);
                if ($statistic == null) {
                    $statistic = new self();
                    $statistic->spot_id = $tmch->DivisionID;
                }
            } else if (Autocolumn::isExist($tmch->DivisionID)) {
                $statistic = self::findOne(['autocolumn_id' => $tmch->DivisionID]);
                if ($statistic == null) {
                    $statistic = new self();
                    $statistic->autocolumn_id = $tmch->DivisionID;
                }
            }
            $statistic->time = isset($tmch->Time) ? preg_replace('/,/','.', $tmch->Time) : 0;
            $statistic->fuel = isset($tmch->Fuel) ? preg_replace('/,/','.', $tmch->Fuel) : 0;
            $statistic->save();
            Console::updateProgress($i, $count);
        }
        Console::endProgress();
    }


    /**
     * @param $divisionId
     * @return array|ActiveRecord|null
     */
    public static function getByDivisionId($divisionId)
    {
        return self::find(['spot_id' => $divisionId])->orWhere(['autocolumn_id' => $divisionId])->one();
    }


    /**
     * @param $divisionId
     * @return bool
     */
    public static function isExist($divisionId) {
        return self::getByDivisionId($divisionId) !== null;
    }

    /**
     * @param $divisionId
     * @return Statistic|array|ActiveRecord|null
     */
    public static function getOrCreate($divisionId)
    {
        return self::isExist($divisionId) ? self::getByDivisionId($divisionId) : new self;
    }


}