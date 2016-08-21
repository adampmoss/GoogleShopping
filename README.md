# Google Shopping Extension for Magento

This is a very basic Google Shopping extension that requires basic Magento development skills to customise - there is little in the way of CMS. The module is designed to be as stripped back as possible to allow flexibility.

### Compatibility

Magento Community Edition 1.4 to 1.9

### Installation

1. Unpack the extension ZIP file in your Magento root directory
2. Clear the Magento cache: **System > Cache Management**
3. Log out the Magento admin and log back in to clear the ACL list
4. Recompile if you are using the Magento Compiler

### Usage

Configure by going to: **System > Configuration > Creare > Google Shopping > Settings**

The extension requires three custom attributes to be created for:

- Brand
- Google Category
- GTIN Number

If these attributes do not exist they should be created and mapped here. If a product has no value for either attribute then it will return the default value set here too.

![GoogleShopping Admin](https://github.com/adampmoss/GoogleShopping/blob/master/screenshot.png)

You can view the feed that shows **all** products at:

    http://www.domain.com/crearegoogleshopping/index/index/

##### Limiting Products

If you would like to show a certain number of products you can use the following. For example this will show 1000 products at a time, starting with page 1:

    http://www.domain.com/crearegoogleshopping/index/index/pagesize/1000/page/1

The next example this will show the next 1000 products (page 2)

    http://www.domain.com/crearegoogleshopping/index/index/pagesize/1000/page/2

##### Filter by Attribute Set

You can now also filter your feed further by adding attributeset/{id} to the end of the URL. Simply pass the attribute set ID as the agrument, for example:

    http://www.domain.com/crearegoogleshopping/index/index/attributeset/10

##### Filter by Custom Attribute

This extension makes it easy for a developer to create further filters for their feeds. Simply edit the method called *getVisibleProducts()* in **app/code/local/Creare/GoogleShopping/Model/Feed.php**

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

### Disable the Module

To disable the module open **app/etc/modules/Creare_GoogleShopping.xml** and in both change this:

    <active>true</active>

to this:

    <active>false</active>

After doing this, clear the cache and reindex your data.

### Developer

- Adam Moss ([@adampmoss](https://twitter.com/adampmoss))
