<?php

use yii\db\Migration;

/**
 * Class m240309_105102_fault_tree_element
 */
class m240309_105102_fault_tree_element extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql')
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%fault_tree_element}}', [
            'id' => $this->primaryKey(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
            'name' => $this->string(),
            'description' => $this->text(),
            'type' => $this->smallInteger()->notNull(),
            'indent_x' => $this->integer(),
            'indent_y' => $this->integer(),
            'diagram' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addForeignKey("fault_tree_element_diagram_fk", "{{%fault_tree_element}}", "diagram",
            "{{%diagram}}", "id", 'CASCADE');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%fault_tree_element}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240309_105102_fault_tree_element cannot be reverted.\n";

        return false;
    }
    */
}
