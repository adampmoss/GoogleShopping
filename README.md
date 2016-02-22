# Google Shopping Extension for Magento

This is a very basic Google Shopping extension that requires Magento development skills to customise - there is little in the way of CMS.

#### Step 1
Install the module using composer or by uploading by FTP

#### Step 2
Configure by going to: **System > Configuration > Creare > Google Shopping > Settings**

The extension requires two attributes to be created for:

- Brand
- Google Category

If these attributes do not exist they should be created and mapped here. If a product has no value for either attribute then it will return the default value set here too.

#### Step 3

You can view the feed that shows all products at:

    http://www.domain.com/crearegoogleshopping/index/index/

If you would like to show a certain number of products you can use the following. For example this will show 1000 products at a time, starting with page 1:

    http://www.domain.com/crearegoogleshopping/index/index/pagesize/1000/page/1

The next example this will show the next 1000 products (page 2)

    http://www.domain.com/crearegoogleshopping/index/index/pagesize/1000/page/2

##### Filter by Attribute Set

You can now also filter your feed further by adding attributeset/{id} to the end of the URL. Simply pass the attribute set ID as the agrument, for example:

    http://www.domain.com/crearegoogleshopping/index/index/attributeset/10

##### Filter by Custom Attribute

This extension makes it easy for a developer to create further filters for their feeds. Simply edit the method called *getVisibleProducts()* in app/code/local/Creare/GoogleShopping/Model/Feed.php

First, add a new argument near the top of the method by assigning to a variable, e.g:

    $brand = $request->getParam('brand');

Then, add the filter collection script before *$collection->clear();*

    if (!empty($brand))
        {
            $collection->addAttributeToFilter('brand', $brand);
        }

You can then use this in your feed URL, for example:

    http://www.domain.com/crearegoogleshopping/index/index/brand/nike

You can combine as many of these filters as you like:

    http://www.domain.com/crearegoogleshopping/index/index/brand/nike/attributeset/10/pagesize/1000/page/2
