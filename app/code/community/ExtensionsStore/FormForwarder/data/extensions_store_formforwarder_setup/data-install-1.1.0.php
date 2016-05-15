<?php

/**
 * 
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_FormForwarder
 * @author     Extensions Store <admin@extensions-store.com>
 */

$installer = $this;
$installer->startSetup();

$blockName = 'extensions_store_formforwarder/formkey';
$permissionBlock = Mage::getModel('admin/block')->load($blockName, 'block_name');
$permissionBlock->setData('block_name', $blockName);
$permissionBlock->setData('is_allowed', 1);
$permissionBlock->save();

$blockName = 'formforwarder/formkey';
$permissionBlock = Mage::getModel('admin/block')->load($blockName, 'block_name');
$permissionBlock->setData('block_name', $blockName);
$permissionBlock->setData('is_allowed', 1);
$permissionBlock->save();

$installer->endSetup();
