# WHMCS Affiliate Coupons v2.1 Alpha

## Prerequisites
Before installing this version you need to remove all the original files from WHMCS Affiliate Coupons <= v2.0, these files include:

* `affcoupons.php`
* `templates/default/images/delete.png`
* `includes/hooks/affcoupons.php`
* `modules/admin/affiliate_coupons/` (remove entire directory)

*YOU ALSO NEED TO REMOVE* this code from `templates/default/affiliates.tpl`

```
<!-- BEGIN AFFILIATE COUPONS CODE -->
{php}
include('affcoupons.php');
{/php}
<!-- END AFFILIATE COUPONS CODE -->
```

If you do not remove the code above from that template you will get an error because that file is no longer used or needed.
