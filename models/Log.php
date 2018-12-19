<?php
/**
 * Created by PhpStorm.
 * User: Броненосец
 * Date: 19.12.2018
 * Time: 20:04
 */

namespace app\models;


use yii\db\ActiveRecord;

class Log extends ActiveRecord
{
    public static function tableName()
    {
        return 'logs';
    }

}