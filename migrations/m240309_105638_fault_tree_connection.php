<?php

use yii\db\Migration;

/**
 * Class m240309_105638_fault_tree_connection
 */
class m240309_105638_fault_tree_connection extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%fault_tree_connection}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'element_from' => $this->integer()->notNull(),
            'element_to' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("element_from_fk", "{{%fault_tree_connection}}", "element_from",
            "{{%fault_tree_element}}", "id", 'CASCADE');
        $this->addForeignKey("element_to_fk", "{{%fault_tree_connection}}", "element_to",
            "{{%fault_tree_element}}", "id", 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fault_tree_connection}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240309_105638_fault_tree_connection cannot be reverted.\n";

        return false;
    }
    */
}
