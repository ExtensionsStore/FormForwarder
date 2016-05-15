<?php

/**
 * Form key block
 * Usage:: <input type="hidden" name="form_key" value="{{block type="extensions_store_formforwarder/formkey"}}" />
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_FormForwarder
 * @author     Extensions Store <admin@extensions-store.com>
 */

class ExtensionsStore_FormForwarder_Block_Formkey extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        return Mage::getSingleton('core/session')->getFormKey();
    }
}