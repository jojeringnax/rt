<?php

use yii\db\Migration;

/**
 * Class m190219_091307_create_table_cras_data
 */
class m190219_091307_create_table_cras_data extends Migration
{

    private $tableName = 'cars_data';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'car_id' => $this->string(36),
            'driver' => $this->string(128)->null(),
            'phone' => $this->string(20)->null(),
            'start_time_plan' => $this->dateTime()->null(),
            'end_time_plan' => $this->dateTime()->null(),
            'work_time_plan' => $this->float(2)->null(),
            'start_time_fact' => $this->dateTime()->null(),
            'work_time_fact' => $this->float(2)->null(),
            'mileage' => $this->integer()->null(),
            'speed' => $this->integer()->null(),
            'fuel_norm' => $this->float(2)->null(),
            'fuel_DUT' => $this->integer()->null(),
            'driver_mark' => $this->string(16)->null(),
            'violations_count' => $this->integer()->null()
        ]);

        $this->addPrimaryKey('pk-spots', $this->tableName, 'car_id');
        $this->addForeignKey('fk-cars_data-car_id-cars-id', $this->tableName, 'car_id', 'cars', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try {
            $this->dropTable($this->tableName);
            return true;
        } catch (Exception $e) {
            echo "m190219_091307_create_table_cras_data cannot be reverted.\n";
            echo $e->getMessage();
            echo $e->getTraceAsString();
            return false;
        }

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190219_091307_create_table_cras_data cannot be reverted.\n";

        return false;
    }
    */
}
