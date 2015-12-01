# Creare Google Shopping

This is a very basic Google Shopping extension that requires Magento development skills to customise - there is little in the way of CMS.

#### Step 1
Install the module using composer or by uploading by FTP

#### Step 2
Configure by going to: **System > Configuration > Creare > Google Shopping > Settings**

The extension requires two attributes to be created for:

- Brand
- Goolge Category

If these attributes do not exist they should be created and mapped here. If a product has no value for either attribute then it will return the default value set here too.

#### Step 3

You can view the feed that shows all products at:

    http://www.domain.com/crearegoogleshopping/index/index/

If you would like to show a certain number of products you can use the following. For example this will show 1000 products at a time, starting with page 1:

    http://www.domain.com/crearegoogleshopping/index/index/pagesize/1000/page/1

The next example this will show the next 1000 products (page 2)

    http://www.domain.com/crearegoogleshopping/index/index/pagesize/1000/page/2