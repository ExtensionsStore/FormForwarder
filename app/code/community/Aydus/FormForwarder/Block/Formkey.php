<?php

/**
 * Form key block
 * Usage:: <input type="hidden" name="form_key" value="{{block type="aydus_formforwarder/formkey"}}" />
 *
 * @category   Aydus
 * @package    Aydus_FormForwarder
 * @author     Aydus Consulting <davidt@aydus.com>
 */

class Aydus_FormForwarder_Block_Formkey extends Mage_Core_Block_Abstract
{
    protected function _toHtml()
    {
        return Mage::getSingleton('core/session')->getFormKey();
    }
}