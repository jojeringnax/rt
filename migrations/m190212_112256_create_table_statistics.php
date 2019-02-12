<?php

use yii\db\Migration;

/**
 * Class m190212_112256_create_table_statistics
 */
class m190212_112256_create_table_statistics extends Migration
{

    private $tableName = 'statistics';


    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->primaryKey(),
            'spot_id' => $this->string(36)->null()->unique(),
            'autocolumn_id' => $this->string(36)->null()->unique(),
            'applications_total' => $this->integer(11)->defaultValue(0),
            'applications_executed' => $this->integer(11)->defaultValue(0),
            'applications_canceled' => $this->integer(11)->defaultValue(0),
            'applications_sub' => $this->integer(11)->defaultValue(0),
            'applications_ac' => $this->integer(11)->defaultValue(0),
            'applications_mp' => $this->integer(11)->defaultValue(0),
            'waybills_total' => $this->integer(11)->defaultValue(0),
            'waybills_processed' => $this->integer(11)->defaultValue(0),
            'accidents_total' => $this->integer(11)->defaultValue(0),
            'accidents_guilty' => $this->integer(11)->defaultValue(0)
        ]);
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
            echo "m190212_112256_create_table_statistics cannot be reverted.\n";
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
        echo "m190212_112256_create_table_statistics cannot be reverted.\n";

        return false;
    }
    */
}
