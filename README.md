<<<<<<< HEAD
# WHMCS Affiliate Coupons v2.1 Alpha
=======
## Version 2.1 Alpha Now Available!
Version 2.1 Alpha is now available, you can find it at the link below as well as instructions on installation.  I recommend using this version over 2.0 from now on.

https://github.com/tripflex/whmcs-affcoupons/tree/2.1alpha

MAKE SURE TO FOLLOW INSTRUCTIONS IF YOU ALREADY HAVE INSTALLED AN OLDER VERSION

## WHMCS 5.2+ Affiliate Coupons 2.0
Author: Myles McNamara (get@smyl.es)
>>>>>>> master

## Prerequisites (required!)
Before installing this version you need to remove all the original files from WHMCS Affiliate Coupons <= v2.0, this will not remove any database entries, and the new 2.1 alpha version will work with all existing database entries.

The files you need to modify or completely remove include:

* `affcoupons.php` this file has multiple vulnerabilities I **strongly** recommend removing it ASAP!
* `aff.php` this file will not be needed in next release but for now remove it and we will replace with version included in this release
* `templates/default/images/delete.png`
* `includes/hooks/affcoupons.php`
* `modules/admin/affiliate_coupons/` (remove entire directory)

**YOU ALSO NEED TO REMOVE** this code from `templates/default/affiliates.tpl`

```
<!-- BEGIN AFFILIATE COUPONS CODE -->
{php}
include('affcoupons.php');
{/php}
<!-- END AFFILIATE COUPONS CODE -->
```

If you do not remove the code above from that template you will get an error because that file is no longer used or needed.

## Installation
Now that you've removed all the files above we can start the installation of the new version.

Download the ZIP file from this link:
https://github.com/tripflex/whmcs-affcoupons/archive/2.1alpha.zip

Open the archive, and inside the `whmcs-affcoupons-2.1alpha` folder should be the `modules` directory and `aff.php` file.

Copy/Move the `modules` directory and the `aff.php` file to the root of your WHMCS installation.

Login to your WHMCS installation, go to the Addon Modules page, and activate the new module.

## A Few Things To Know
Make sure to check the admin page for Affiliate Coupons to see if updates are available, there will be a notification at top of page when a new version is available.

With this release almost everything has been redone code wise, including sanitizing and validating user data which was not done previously.

The affiliates page is now loaded using jQuery on the affiliates page, with the next release everything will be done through ajax on affiliates page.

If you want to provide direct link to just the affiliate promo code page, it is all contained on this page:

`http://yourdomain.com/index.php?m=affcoupons`

This release loads that page using jQuery, selects the DIV for content, and then inserts it at the bottom of the Affiliates page.  When adding coupon, updating landing page, etc, from affiliates page it will POST to the `index.php?m=affcoupons` page.

## PLEASE REPORT ANY BUGS OR ISSUES HERE ON GITHUB!
