<?php

class Creare_GoogleShopping_Model_Feed extends Mage_Core_Model_Abstract
{
	protected function _construct()
    {
        $this->_init('crearegoogleshopping/feed');
    }

    public function getConfig($value)
    {
    	return Mage::getStoreConfig('crearegoogleshopping/settings/'.$value);
    }
	
	public function printToXml()
	{
		$store = Mage::app()->getStore();
		$currency = $store->getCurrentCurrencyCode();
		$attributeSetModel = Mage::getModel("eav/entity_attribute_set");
		$bundlePriceModel = Mage::getModel('bundle/product_price');
		
		echo $this->xmlHeader($store);
		
		foreach ($this->getVisibleProducts($store) as $product)
		{ 
			$attributeSetModel->load($product->getAttributeSetId());
			$attributeSetName  = $attributeSetModel->getAttributeSetName();
		?><item>
                        <title><![CDATA[<?php echo $product->getName(); ?>]]></title>
                        <link><?php echo $product->getProductUrl() ?></link>
                        <description>
                        <![CDATA[
                        <?php echo substr(Mage::helper('core')->escapeHtml(strip_tags($product->getDescription())), 0, 5000) ?><br />
                        ]]>
                        </description>
                        <g:image_link><?php echo Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA).'catalog/product'.$product->getImage() ?></g:image_link>
                        <?php echo $this->getPriceXml($product, $currency, $bundlePriceModel) ?>
                        <g:availability>in stock</g:availability>
                        <g:condition>new</g:condition>
                        <g:id><?php echo $product->getSku() ?></g:id>
                        
                        <g:product_type>
                        <![CDATA[
                        <?php echo $attributeSetName ?>
                        ]]>
                        </g:product_type>
                        
                        <g:google_product_category>
                        <![CDATA[
                        <?php if ($product->getData($this->getConfig('google_category_code'))) : ?>
                        	<?php echo str_replace(">", "&gt;", $product->getResource()->getAttribute($this->getConfig('google_category_code'))->getFrontend()->getValue($product)) ?>
                        <?php else: ?>
                        	<?php echo $this->getConfig('default_category') ?>
                        <?php endif ?>
                        ]]>
                        </g:google_product_category>
                       <g:brand><![CDATA[
                        <?php if ($product->getData($this->getConfig('brand_code'))) : ?>
                        	<?php echo $product->getResource()->getAttribute($this->getConfig('brand_code'))->getFrontend()->getValue($product) ?>
                        <?php else: ?>
                        	<?php echo $this->getConfig('default_brand') ?>
                        <?php endif ?>
                        ]]></g:brand>
                        <g:mpn><?php echo $product->getSku() ?></g:mpn>
                        <g:tax>
						 <g:country>UK</g:country>
						 <g:rate>0</g:rate>
						 <g:tax_ship>n</g:tax_ship>
						</g:tax>
						<g:shipping>
						 <g:country>UK</g:country>
						 <g:price>0 GBP</g:price>
						</g:shipping>
						<g:identifier_exists>TRUE</g:identifier_exists>
                    </item><?php }
				
		echo $this->xmlFooter();
	}
	
	protected function getVisibleProducts($store)
	{
		$request = Mage::app()->getRequest();

		$curPage = $request->getParam('page');
		$pageSize = $request->getParam('pagesize');
		$attributeSet = $request->getParam('attributeset');

		$collection = Mage::getModel('catalog/product')->getCollection()
			 ->addAttributeToSelect('*')
			 ->addAttributeToFilter('status', 1)		// Enabled
			 ->addAttributeToFilter('visibility', 4)	// Catalog/search
			 ->setStore($store->getId())
			 ->addMinimalPrice()
			 ->setPageSize($pageSize);	// Sets number of products to 1000

		if (!empty($curPage))
		{
			$collection->setCurPage($curPage);
			$collection->setPageSize($pageSize);
		}

		if (!empty($attributeSet))
		{
			$collection->addAttributeToFilter('attribute_set_id', $attributeSet);
		}

		$collection->clear();

		return $collection;
	}
	
	protected function xmlHeader($store)
	{
		header("Content-Type: application/xml; charset=utf-8");
		
		$html =  '<rss version="2.0" xmlns:g="http://base.google.com/ns/1.0">';
		$html .= '<channel>';
		$html .= '<title>'.$store->getName().'</title>';
		$html .= '<link>'.Mage::getBaseUrl().'</link>';
    	$html .= '<description>'.$store->getName().'</description>';
		
		return $html;	
	}
	
	protected function xmlFooter()
	{
		$html =  '</channel></rss>';
		return $html;	
	}
	
	protected function getPriceXml($product, $currency, $bundlePriceModel)
	{
		switch($product->getTypeId())
		{
			case 'grouped':
				return $this->groupedProductPrice($product, $currency);
			break;

			case 'bundle':
				return $this->bundleProductPrice($product, $currency, $bundlePriceModel);
			break;

			default:
				return $this->simpleProductPrice($product, $currency);
		}
	}

	protected function groupedProductPrice($product, $currency)
	{

		return '<g:price>'.Mage::helper('tax')->getPrice($product, $product->getMinimalPrice()).' '.$currency.'</g:price>';
	}

	protected function bundleProductPrice($product, $currency, $bundlePriceModel)
	{
		return '<g:price>'.$bundlePriceModel->getTotalPrices($product,'min',1).' '.$currency.'</g:price>';
	}

	protected function simpleProductPrice($product, $currency)
	{
		$html = "";

		if ($product->getSpecialPrice) 
		{
			$html = '<g:price>'.Mage::helper('tax')->getPrice($product, $product->getPrice()).' '.$currency.'</g:price>';
			$html .= '<g:sale_price>'.Mage::helper('tax')->getPrice($product, $product->getSpecialPrice()).' '.$currency.'</g:sale_price>';	
		} else {
			$html = '<g:price>'.Mage::helper('tax')->getPrice($product, $product->getFinalPrice()).' '.$currency.'</g:price>';
		}

		return $html;
	}
}