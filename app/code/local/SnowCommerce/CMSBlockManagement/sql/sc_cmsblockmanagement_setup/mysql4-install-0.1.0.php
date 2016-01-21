<?php
/**
 * Produced by SnowCommerce development team
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->getConnection()
    ->dropColumn($this->getTable('cms/block'),'sc_keep_versions');
$installer->getConnection()
    ->dropColumn($this->getTable('cms/block'),'sc_block_label');
$installer->getConnection()
    ->addColumn($this->getTable('cms/block'), 'sc_keep_versions', 'BOOLEAN');
$installer->getConnection()
    ->addColumn($this->getTable('cms/block'),'sc_block_label',Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'nullable'  => true,
    ));

$installer->getConnection()->dropIndex(
    $installer->getTable('cms/block'),
    'IDX_BLOCK_IDENTIFIER'
);

$installer
    ->getConnection()
    ->addKey(
        $installer->getTable('cms/block'),
        'IDX_BLOCK_IDENTIFIER',
        'identifier'
    );

$installer->getConnection()
    ->dropTable($installer->getTable('sc_cmsblockmanagement/versions'));
$table = $installer->getConnection()
    ->newTable($installer->getTable('sc_cmsblockmanagement/versions'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'          => true,
        'nullable'          => false,
        'primary'           => true,
        'auto_increment'    => true,
    ), 'Version ID')
    ->addColumn('block_identifier', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
        'nullable'  => false,
    ), 'Block Identifier')
    ->addColumn('version', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Version')
    ->addColumn('content', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    ), 'Content')
    ->addColumn('admin_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Admin Name')
    ->addColumn('is_actual', Varien_Db_Ddl_Table::TYPE_BOOLEAN, 'Is Actual')
    ->addIndex(
        $installer->getIdxName(
            'sc_cmsblockmanagement/versions',
            array('block_identifier')
        ),
        array('block_identifier'), array('block_identifier'))
    ->addForeignKey(
        $installer->getFkName('sc_cmsblockmanagement/versions', 'block_identifier', 'cms/block', 'identifier'),
        'block_identifier', $installer->getTable('cms/block'), 'identifier',
        Varien_Db_Ddl_Table::ACTION_CASCADE, Varien_Db_Ddl_Table::ACTION_CASCADE)
    ->setComment('Block Versions Table')
    ->setOption('ENGINE','InnoDB');
$installer->getConnection()->createTable($table);

$installer->getConnection()
    ->dropTable($installer->getTable('sc_cmsblockmanagement/labels'));
$table = $installer->getConnection()
    ->newTable($installer->getTable('sc_cmsblockmanagement/labels'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'          => true,
        'nullable'          => false,
        'primary'           => true,
        'auto_increment'    => true,
    ), 'Label ID')
    ->addColumn('label_name', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Label Name')
    ->addColumn('label_color', Varien_Db_Ddl_Table::TYPE_VARCHAR, 10, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Label Color')
    ->setComment('Block Labels Table')
    ->setOption('ENGINE', 'InnoDB');

$installer->getConnection()->createTable($table);

$installer->endSetup();