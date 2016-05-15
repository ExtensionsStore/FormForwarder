<?php

/**
 * FormForwarder controller
 *
 * @category   ExtensionsStore
 * @package    ExtensionsStore_FormForwarder
 * @author     Extensions Store <admin@extensions-store.com>
 */

class ExtensionsStore_FormForwarder_IndexController extends Mage_Core_Controller_Front_Action
{
    const XML_PATH_EMAIL_RECIPIENT  = 'contacts/email/recipient_email';
    const XML_PATH_EMAIL_SENDER     = 'contacts/email/sender_email_identity';
    
    public function indexAction()
    {        
        $refererUrl = $this->_getRefererUrl();
        
        if (!$this->_validateFormKey()) {
            
            Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
            $this->getResponse()->setRedirect($refererUrl);      
            
            return;
        }
        
        $post = $this->getRequest()->getPost();
        
        if ( $post ) {
            
            $translate = Mage::getSingleton('core/translate');
            $translate->setTranslateInline(false);
            
            try {
                
                $postObject = new Varien_Object();
                $postObject->setData($post);
                
                $templateCode = $post['template_code'];
                $emailTemplate = Mage::getModel('adminhtml/email_template');
                $emailTemplate->load($templateCode,'template_code');
                $emailTemplateId = $emailTemplate->getId();
                
                $storeId = Mage::app()->getStore()->getId();
                $recipient = (Zend_Validate::is(trim($post['forward']), 'EmailAddress')) ? $post['forward'] : Mage::getStoreConfig(self::XML_PATH_EMAIL_RECIPIENT, $storeId);
                
                $mailTemplate = Mage::getModel('core/email_template');
                $mailTemplate->setDesignConfig(array('area' => 'frontend'))->setReplyTo($post['Email']);                
                
                if (is_array($_FILES) && count($_FILES) > 0){
                    
                    foreach ($_FILES as $file){
                        
                        foreach ($file['name'] as $i=>$filename){
                            
                            $error = $file['error'][$i];
                            
                            if (!$error){
                                
                                $type = $file['type'][$i];
                                $tmpName = $file['tmp_name'][$i];
                                $fileContents = file_get_contents($tmpName);
                                $attachment = $mailTemplate->getMail()->createAttachment($fileContents,$type,Zend_Mime::DISPOSITION_ATTACHMENT, Zend_Mime::ENCODING_BASE64, $filename);
                                
                            }
                            
                        }
                        
                    }
                    
                }
                
                $mailTemplate
                    ->sendTransactional(
                        $emailTemplateId,
                        Mage::getStoreConfig(self::XML_PATH_EMAIL_SENDER),
                        $recipient,
                        null,
                        array('data' => $postObject)
                    );

                if (!$mailTemplate->getSentSuccess()) {
                    throw new Exception();
                }

                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addSuccess(Mage::helper('contacts')->__('Your inquiry was submitted and will be responded to as soon as possible. Thank you for contacting us.'));
                
                $redirect = ($post['redirect']) ? Mage::getUrl($post['redirect']) : $refererUrl;
                
                $this->getResponse()->setRedirect($redirect);
                return;
                
            } catch (Exception $e) {
                
                $translate->setTranslateInline(true);

                Mage::getSingleton('customer/session')->addError(Mage::helper('contacts')->__('Unable to submit your request. Please, try again later'));
                $this->getResponse()->setRedirect($refererUrl);
                return;
            }

        } else {
            
            $this->getResponse()->setRedirect($refererUrl);
            
        }
        
    }

}