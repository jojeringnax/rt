<?php

use yii\db\Migration;

/**
 * Class m190403_114103_create_table_bad_spots
 */
class m190403_114103_create_table_bad_spots extends Migration
{


    private $tableName = 'bad_spots';

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
            'name' => $this->string(128)->null(),
            'town' => $this->string(32)->null(),
            'address' => $this->text(),
            'x_pos' => $this->float(6),
            'y_pos' => $this->float(6)
        ]);
        $this->addPrimaryKey('pk-spots', $this->tableName, 'id');
        $this->addForeignKey('fk-bad_spots-organization_id-autocolumn-id', $this->tableName, 'autocolumn_id', 'autocolumns', 'id');
        $this->addForeignKey('fk-bad_spots-organization_id-organizations-id', $this->tableName, 'organization_id', 'organizations', 'id');
        $this->addForeignKey('fk-bad_spots-company_id-companies-id', $this->tableName, 'company_id', 'companies','id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        try {
            $this->dropPrimaryKey('pk-spots', $this->tableName);
            $this->dropForeignKey('fk-bad_spots-organization_id-autocolumn-id', $this->tableName);
            $this->dropForeignKey('fk-bad_spots-organization_id-organizations-id', $this->tableName);
            $this->dropForeignKey('fk-bad_spots-company_id-companies-id', $this->tableName);
            $this->dropTable($this->tableName);
            return true;
        } catch (Exception $e) {
            echo "m190403_114103_create_table_bad_spots cannot be reverted.\n";
            echo $e->getMessage();
            echo $e->getTraceAsString();
            return false;
        }

    }
}
