<?php
/**
 * Created by PhpStorm.
 * User: Броненосец
 * Date: 12.02.2019
 * Time: 15:13
 */

namespace app\commands;


use app\models\Statistic;
use yii\console\Controller;
use yii\db\Exception;

class StatsController extends Controller
{
    public $all = 0;
    public $applications = 0;
    public $waybills = 0;
    public $accidents = 0;
    public $tmch = 0;
    public $monitoring = 0;

    public function options($actionID)
    {
        return ['all', 'applications', 'waybills', 'accidents', 'tmch', 'monitoring'];
    }

    public function optionAliases()
    {
        return ['a' => 'all', 'p' => 'applications', 'w' => 'waybills', 'd' => 'accidents', 't' => 'tmch', 'm' => 'monitoring'];
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function actionIndex()
    {
        if ($this->all) {
            Statistic::getAllStats();
            echo 'Finished';
            return true;
        }
        $client = new \SoapClient('http://d.rg24.ru:5601/PUP_WS/ws/PUP.1cws?wsdl');
        if ($this->applications) {
            Statistic::getApplications($client);
            echo 'Finished';
            return true;
        }
        if ($this->waybills) {
            Statistic::getWaybills($client);
            echo 'Finished';
            return true;
        }
        if ($this->tmch) {
            Statistic::getTMCH($client);
        }
        if ($this->monitoring) {
            Statistic::getWBs($client);
        }
        throw new Exception('Nothing to do here');
    }

}