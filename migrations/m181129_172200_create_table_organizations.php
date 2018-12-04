<?php

use yii\db\Migration;

/**
 * Class m181129_172200_create_table_organizations
 */
class m181129_172200_create_table_organizations extends Migration
{
    private $tableName = 'organizations';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' =>  $this->string(36),
            'company_id' => $this->string(36),
            'description' => $this->string(512),
            'x_pos' => $this->float(6),
            'y_pos' => $this->float(6)
        ]);
        $this->addPrimaryKey('pk-organizations', $this->tableName, 'id');
        $this->addForeignKey('fk-organizations-company_id-companies-id', $this->tableName, 'company_id', 'companies','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try {
            $this->dropForeignKey('fk-organizations-company_id-companies-id', $this->tableName);
            $this->dropTable($this->tableName);
            return true;
        } catch (Exception $e) {
            echo "m181129_172200_create_table_organizations cannot be reverted.\n";
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
        echo "m181129_172200_create_table_organizations cannot be reverted.\n";

        return false;
    }
    */
}
