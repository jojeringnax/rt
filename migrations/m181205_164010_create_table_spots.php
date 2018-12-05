<?php

use yii\db\Migration;

/**
 * Class m181205_164010_create_table_spots
 */
class m181205_164010_create_table_spots extends Migration
{


    private $tableName = 'spots';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->string(36),
            'company_id' => $this->string(36),
            'organization_id' => $this->string(36),
            'autocolumn_id' => $this->string(36),
            'description' => $this->string(512),
            'address' => $this->text(),
            'x_pos' => $this->float(6),
            'y_pos' => $this->float(6)
        ]);
        $this->addPrimaryKey('pk-spots', $this->tableName, 'id');
        $this->addForeignKey('fk-spots-organization_id-autocolumn-id', $this->tableName, 'autocolumn_id', 'autocolumns', 'id');
        $this->addForeignKey('fk-spots-organization_id-organizations-id', $this->tableName, 'organization_id', 'organizations', 'id');
        $this->addForeignKey('fk-spots-company_id-companies-id', $this->tableName, 'company_id', 'companies','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try {
            $this->dropPrimaryKey('pk-spots', $this->tableName);
            $this->dropForeignKey('fk-spots-organization_id-autocolumn-id', $this->tableName);
            $this->dropForeignKey('fk-spots-organization_id-organizations-id', $this->tableName);
            $this->dropForeignKey('fk-spots-company_id-companies-id', $this->tableName);
            $this->dropTable($this->tableName);
            return true;
        } catch (Exception $e) {
            echo "m181205_164010_create_table_spots cannot be reverted.\n";
            echo $e->getMessage();
            echo $e->getTraceAsString();
            return false;
        }

        /*
        // Use up()/down() to run migration code without a transaction.
        public function up()
        {

        }

        public function down()
        {
            echo "m181205_164010_create_table_spots cannot be reverted.\n";

            return false;
        }
        */
    }
}
