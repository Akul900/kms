<?php

use yii\db\Migration;

/**
 * Class m240309_110944_fault_parameter
 */
class m240309_110944_fault_parameter extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%fault_parameter}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'operator' => $this->smallInteger()->notNull()->defaultValue(0),
            'value' => $this->text(),
            'fault' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("fault_parameter_fault_fk", "{{%fault_parameter}}",
            "fault", "{{%fault_tree_element}}", "id", 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fault_parameter}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240309_110944_fault_parameter cannot be reverted.\n";

        return false;
    }
    */
}
