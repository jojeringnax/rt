<?php

use yii\db\Migration;

/**
 * Class m181129_161431_create_table_companies
 */
class m181129_161431_create_table_companies extends Migration
{
    private $tableName = 'companies';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
           'id' => $this->string(36),
           'name' => $this->string(256)
        ]);

        $this->addPrimaryKey('pk-companies', $this->tableName, 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try {
            $this->dropPrimaryKey('pk-companies', $this->tableName);
            $this->dropTable($this->tableName);
            return true;
        } catch (Exception $e) {
            echo "m181129_161431_create_table_companies cannot be reverted.\n";
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
        echo "m181129_161431_create_table_companies cannot be reverted.\n";

        return false;
    }
    */
}
