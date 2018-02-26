# WHMCS Affiliate Coupons v2.1.2
* Current Stable Version: 2.1.2
* Author: Myles McNamara (get@smyl.es)

Forked from Affiliate Coupons 1.2
-----
Affiliate Coupons 1.2 - WHMCS Module
Written by: Frank Laszlo <frank@asmallorange.com>
License: SEE LICENSE FILE

Starting with version >= 2.1.0 is a complete rewrite of the code base.

**MAKE SURE TO FOLLOW INSTRUCTIONS IF YOU ALREADY HAVE INSTALLED AN OLDER VERSION**

THIS PROJECT IS NO LONGER MAINTAINED OR UPDATED, PLEASE FEEL FREE TO FORK AS LONG AS YOU MAINTAIN LICENSE

## Description
This module will allow coupon codes to be used instead of affiliate URLs
for granting referrals to your affiliates. This is handy for affiliates who
advertise "offline," where it is much easier to give someone a coupon code
rather than a long URL string for your referral. The coupons will also grant
the user a discount that can be defined by the administrator.

### Screenshots
<table>
	<td width="50%">
		<img src="https://smyl.es/img/Selection-1130x736-12.png" alt="Admin Area View">
	</td>
	<td width="50%">
		<img src="https://smyl.es/img/Selection-1002x631-11.png" alt="Client Area Affiliates View">
	</td>
</table>

## Prerequisites (required if upgrade from <= v2.0.0)
Before installing this version you need to remove all the original files from WHMCS Affiliate Coupons <= v2.0, this will not remove any database entries, and the new 2.1 version will work with all existing database entries.

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
{/php}<
<!-- END AFFILIATE COUPONS CODE -->
```

If you do not remove the code above from that template you will get an error because that file is no longer used or needed.

## Installation
Now that you've removed all the files above we can start the installation of the new version.

Download the ZIP file from this link:
https://github.com/tripflex/whmcs-affcoupons/archive/master.zip

Open the archive, and inside the `whmcs-affcoupons-master` folder should be the `modules` directory and `aff.php` file.

Copy/Move the `modules` directory and the `aff.php` file to the root of your WHMCS installation.

Login to your WHMCS installation, go to the Addon Modules page, and activate the new module.

## A Few Things To Know
Make sure to check the admin page for Affiliate Coupons to see if updates are available, there will be a notification at top of page when a new version is available.

With this release almost everything has been redone code wise, including sanitizing and validating user data which was not done previously.

The affiliates page is now loaded using jQuery on the affiliates page, with the next release everything will be done through ajax on affiliates page.

If you want to provide direct link to just the affiliate promo code page, it is all contained on this page:

`http://yourdomain.com/index.php?m=affcoupons`

This release loads that page using jQuery, selects the DIV for content, and then inserts it at the bottom of the Affiliates page.  When adding coupon, updating landing page, etc, from affiliates page it will POST to the `index.php?m=affcoupons` page.

### PLEASE REPORT ANY BUGS OR ISSUES HERE ON GITHUB!

## Compatibility
Some themes have been reported to have issues with this addon.  This is **NOT** and I want to repeat, **NOT** an issue with Affiliate Coupons, it is the theme itself.  As long as your theme supports the standard default WHMCS module pages you should not have any problems.  Below you will find fixes for themes that have been found to have issues with supporting standard WHMCS module pages.

### WHMCSThemes.com

Add this code to the top of your `header.tpl` file:

```smarty
{php}
    if($_GET['m']) $this->assign('filename', 'modulepage');
{/php}
```


## Changelog
**v2.1.2**
- Fix JS issue not detecting affiliates page

**v2.1.1**
- Updated WHMCSe Framework
- Added update notice on dashboard
- Fixed problems with HTTPS
- Fixed problems with affiliate coupon output on affiliates.php

**v2.1.0**
- Codebase completely rewritten, not compatible with v2.0.0

**v2.0.0**
- Updated version 1 to support new versions of WHMCS
