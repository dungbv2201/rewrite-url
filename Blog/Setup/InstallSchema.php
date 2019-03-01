<?php

namespace Dung\Blog\Setup;


use Magento\Framework\DB\Ddl\Table;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;



/**
 * Class InstallSchema
 * @package Dungbv\Blog\Setup
 */
class InstallSchema implements InstallSchemaInterface
{
    /**
     * @param SchemaSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return string|void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();
        $tableName = $installer->getTable("blog");
        $conn = $installer->getConnection();
        $table = $conn->newTable($tableName)
            ->addColumn(
                'id',
                Table::TYPE_INTEGER,
                null,
                ['identity'=>true,'primary'=>true,'nullable'=>false,'unsigned'=>true]
            )
            ->addColumn(
                'store_id',
                Table::TYPE_INTEGER,
                null,
                ['nullable'=>false,'unsigned'=>true]
            )
            ->addColumn(
                'title',
                Table::TYPE_TEXT,
                72,
                ['nullable'=>true]
            )
            ->addColumn(
                'slug',
                Table::TYPE_TEXT,
                255,
                ['nullable'=>false]
            )
            ->addColumn(
                'description',
                Table::TYPE_TEXT,
                500,
                ['nullable'=>true]
            )
            ->addColumn(
                'content',
                Table::TYPE_TEXT,
                '2M',
                ['nullable'=>false]
            )
            ->addColumn(
                'image',
                Table::TYPE_TEXT,
                255,
                ['nullable'=>true]
            )
            ->addColumn(
                'start_date',
                Table::TYPE_DATETIME,
                null,
                ['nullable'=>true]
            )
            ->addColumn(
                'end_date',
                Table::TYPE_DATETIME,
                null,
                ['nullable'=>true]
            )
            ->addColumn(
                'created_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false,'default'=>Table::TIMESTAMP_INIT]
            )
            ->addColumn(
                'updated_at',
                Table::TYPE_TIMESTAMP,
                null,
                ['nullable' => false,'default'=>Table::TIMESTAMP_INIT_UPDATE]
            )->addIndex(
                $installer->getIdxName(
                    $tableName,
                    ['slug'],
                    AdapterInterface::INDEX_TYPE_UNIQUE
                ),
                'slug',
                ['type' =>AdapterInterface::INDEX_TYPE_UNIQUE]
            )->addIndex(
                $installer->getIdxName(
                    $tableName,
                    ['title'],
                    AdapterInterface::INDEX_TYPE_FULLTEXT
                ),
                'title',
                ['title' =>AdapterInterface::INDEX_TYPE_FULLTEXT]
            )
            ->setOption('charset','utf8');
        $conn->createTable($table);
        $installer->endSetup();
    }
}