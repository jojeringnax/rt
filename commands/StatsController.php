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

    public function options($actionID)
    {
        return ['all', 'applications', 'waybills', 'accidents'];
    }

    public function optionAliases()
    {
        return ['a' => 'all', 'p' => 'applications', 'w' => 'waybills', 'd' => 'accidents'];
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
        if ($this->applications) {
            Statistic::getApplications();
            echo 'Finished';
            return true;
        }
        if ($this->waybills) {
            Statistic::getWaybills();
            echo 'Finished';
            return true;
        }
        throw new Exception('Nothing to do here');
    }

}