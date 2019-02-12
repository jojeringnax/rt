<?php

use yii\db\Migration;

/**
 * Class m181205_164227_create_table_cars
 */
class m181205_164227_create_table_cars extends Migration
{
    private $tableName = 'cars';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' =>  $this->string(36),
            'spot_id' => $this->string(36)->null(),
            'number' => $this->string(15)->null(),
            'type' => $this->tinyInteger(1)->null(),
            'model' => $this->string(32)->null(),
            'description' => $this->string(512)->null(),
            'status' => $this->text()->null(),
            'inline' => $this->boolean()->null(),
            'year' => $this->integer(4)->null(),
            'profitability' => $this->float(2)->null(),
            'technical_inspection_days' => $this->integer(6)->null(),
            'battery_change_days' => $this->integer(6)->null(),
            'tire_change_days' => $this->integer(6)->null(),
            'tire_season' => $this->string(64)->null(),
            'terminal' => $this->boolean()->null(),
            'x_pos' => $this->float(8)->null(),
            'y_pos' => $this->float(8)->null()
        ]);
        $this->addPrimaryKey('pk-cars', $this->tableName, 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try {
            $this->dropPrimaryKey('pk-cars', $this->tableName);
            $this->dropTable($this->tableName);
            return true;
        } catch (Exception $e) {
            echo "m181204_090819_create_table_cars cannot be reverted.\n";
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
        echo "m181205_164227_create_table_cars cannot be reverted.\n";

        return false;
    }
    */
}
