<?php

use yii\db\Migration;

class m161206_131451_alter_event_create_devices_folders extends Migration
{
    public function up()
    {

	$this->addColumn('events', 's_IP', $this->string());
	$this->addColumn('events', 'd_IP', $this->string());
	$this->addColumn('events', 's_port', $this->integer()->unsigned());
	$this->addColumn('events', 'd_port', $this->integer()->unsigned());
	$this->addColumn('events', 'severity', $this->integer()->unsigned());

        $this->addCommentOnColumn('events', 'description', 'correl_log');


	$this->createDeviceGroupsTable();
        $this->createDevicesTable();
        $this->createLogsTable();
        $this->createRelEventLogTable();		
    }

    private function createLogsTable()
    {
        $this->createTable('logs', [           
            'id' => $this->primaryKey()->unsigned(),
            'filename' => $this->string(),
            'timestamp' => $this->timestamp(),
            'device_id' => $this->integer()->unsigned()->null(),
            
        ]);

        $this->createIndex('idx_L_device_id', 'logs', 'device_id');

        $this->addForeignKey('fk_L_device_id', 'logs', 'device_id', 'devices', 'id', 'SET NULL', 'SET NULL');
    }

    private function createRelEventLogTable()
    {
        $this->createTable('rel_event_log', [
            'event_id' => $this->integer()->unsigned(),
            'log_id' => $this->integer()->unsigned(),
        ]);

        $this->createIndex('idx_EL_event_id', 'rel_event_log', 'event_id');
        $this->createIndex('idx_EL_log_id', 'rel_event_log', 'log_id');

        $this->addForeignKey('fk_EL_event_id', 'rel_event_log', 'event_id', 'events', 'id', 'CASCADE', 'CASCADE');
        $this->addForeignKey('fk_EL_log_id', 'rel_event_log', 'log_id', 'logs', 'id', 'CASCADE', 'CASCADE');
    }

    private function createDevicesTable()
    {
        $this->createTable('devices', [           
            'id' => $this->primaryKey()->unsigned(),
            'friendly_name' => $this->string(),
            'name' => $this->string(),
            'ip_address' => $this->string(),
            'group_id' => $this->integer()->unsigned()->null(),
          ]);

        $this->createIndex('idx_D_device_id', 'devices', 'group_id');

        $this->addForeignKey('fk_D_group_id', 'devices', 'group_id', 'device_groups', 'id', 'SET NULL', 'SET NULL');
    }

    private function createDeviceGroupsTable()
    {
        $this->createTable('device_groups', [           
            'id' => $this->primaryKey()->unsigned(),
            'name' => $this->string(),           
            'severity' => $this->integer()->unsigned()->defaultValue(1), 
        ]);
    }

    public function down()
    {
        $this->dropTable('device_groups');
        $this->dropTable('rel_event_log');
        $this->dropTable('logs');
        $this->dropTable('devices');
    }


    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
