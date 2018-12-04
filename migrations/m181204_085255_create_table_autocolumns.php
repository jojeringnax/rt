<?php

use yii\db\Migration;

/**
 * Class m181204_085255_create_table_autocolumns
 */
class m181204_085255_create_table_autocolumns extends Migration
{

    private $tableName = 'autocolumns';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->tableName, [
            'id' => $this->string(36),
            'company_id' => $this->string(36),
            'spot_id' => $this->string(36),
            'description' => $this->string(512),
            'address' => $this->text(),
            'x_pos' => $this->float(6),
            'y_pos' => $this->float(6)
        ]);
        $this->addPrimaryKey('pk-autocolumns', $this->tableName, 'id');
        $this->addForeignKey('fk-autocolumns-organization_id-spots-id', $this->tableName, 'spot_id', 'spots', 'id');
        $this->addForeignKey('fk-autocolumns-company_id-companies-id', $this->tableName, 'company_id', 'companies','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181204_085255_create_table_autocolumns cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181204_085255_create_table_autocolumns cannot be reverted.\n";

        return false;
    }
    */
}
