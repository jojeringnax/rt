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
            'spot_id' => $this->integer(36),
            'number' => $this->string(15),
            'type' => $this->tinyInteger(1),
            'model' => $this->string(32),
            'description' => $this->string(512),
            'year' => $this->integer(4),
            'x_pos' => $this->float(6),
            'y_pos' => $this->float(6)
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
