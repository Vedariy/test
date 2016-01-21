<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer -> startSetup();

$table = $installer->getConnection()
    ->newTable($installer->getTable('sc_cmspagesversions'))
    ->addColumn('entity_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'          => true,
        'nullable'          => false,
        'primary'           => true,
        'auto_increment'    => true,
    ), 'Version ID')
    ->addColumn('page_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Page ID')
    ->addColumn('version', Varien_Db_Ddl_Table::TYPE_DATETIME, null, array(
        'unsigned'  => true,
        'nullable'  => false,
    ), 'Version')
    ->addColumn('content', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
    ), 'Content')
    ->addColumn('admin_name', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Admin Name')
    ->addColumn('identifier', Varien_Db_Ddl_Table::TYPE_TEXT, 255, array(
    ), 'Identifier')
    ->addColumn('is_actual', Varien_Db_Ddl_Table::TYPE_BOOLEAN, 'Is Actual')
    ->setComment('Pages Version Table');

$installer->getConnection()->createTable($table);

$installer->getConnection()->addColumn(
    $this->getTable('cms_page'), //table name
    'sc_keep_versions',      //column name
    'BOOLEAN'  //datatype definition
);
$installer->endSetup();