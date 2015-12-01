<?php

class Creare_GoogleShopping_IndexController extends Mage_Core_Controller_Front_Action
{
	public function indexAction()
	{
		if (Mage::getStoreConfig('crearegoogleshopping/settings/enabled'))
		{
			Mage::getModel('crearegoogleshopping/feed')->printToXml();
		} else {
			$this->_redirect('/');
		}
	}
}