<?php

use think\migration\Migrator;

class ExtensionAuthPermission extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change(): void
    {
        $table = $this->table('extension_auth_permission', [
            'engine' => 'InnoDB',
            'collation' => 'utf8mb4_general_ci',
            'comment' => '权限表',
        ]);
        $table
            ->addColumn('name', 'string', [
                'limit' => 255,
                'default' => '',
                'comment' => '权限名称',
            ])
            ->addColumn('rule', 'string', [
                'limit' => 50,
                'default' => '',
                'comment' => '权限规则',
            ])
            ->addColumn('route', 'string', [
                'limit' => 255,
                'default' => '',
                'comment' => '权限路由',
            ])
            ->addColumn('method', 'string', [
                'limit' => 50,
                'default' => '',
                'comment' => '权限方法',
            ])
            ->addColumn('description', 'string', [
                'limit' => 255,
                'default' => '',
                'comment' => '权限描述',
            ])
            ->addTimestamps()
            ->addIndex('route', ['unique' => true, 'name' => 'uniq_route'])
            ->create();
    }
}
