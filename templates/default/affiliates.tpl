{if $inactive}

<p>{$LANG.affiliatesdisabled}</p>

{else}

<p align="center">{$LANG.affiliatesrealtime}</p>

<table cellspacing="1" cellpadding="0" class="frame"><tr><td>
<table width="100%" cellpadding="2">
<tr><td width="30%" class="fieldarea">{$LANG.affiliatesvisitorsreferred}</td><td width="20%">{$visitors}</td><td width="30%" class="fieldarea">{$LANG.affiliatescommissionspending}</td><td width="20%">{$pendingcommissions}</td></tr>
<tr><td class="fieldarea">{$LANG.affiliatessignups}</td><td>{$signups}</td><td class="fieldarea">{$LANG.affiliatescommissionsavailable}</td><td>{$balance}</td></tr>
<tr><td class="fieldarea">{$LANG.affiliatesconversionrate}</td><td>{$conversionrate}%</td><td class="fieldarea">{$LANG.affiliateswithdrawn}</td><td>{$withdrawn}</td></tr>
</table>
</td></tr></table>

<p align="center"><strong>{$LANG.affiliatesreferallink}:</strong> <input type="text" size="60" value="{$referrallink}"></p>

{if $withdrawrequestsent}
<p align="center">{$LANG.affiliateswithdrawalrequestsuccessful}</p>
{else}
{if $withdrawlevel}<p align="center"><input type="button" value="{$LANG.affiliatesrequestwithdrawal}" onclick="window.location='{$smarty.server.PHP_SELF}?action=withdrawrequest'" class="buttonwarn" /></p>{/if}
{/if}

<p class="heading2">{$LANG.affiliatesreferals}</p>

<table align="center" class="clientareatable" cellspacing="1">
<tr class="clientareatableheading"><td>{$LANG.affiliatessignupdate}</td><td>{$LANG.orderproduct}</td><td>{$LANG.affiliatesamount}</td><td>{$LANG.orderbillingcycle}</td><td>{$LANG.affiliatescommission}</td><td>{$LANG.affiliatesstatus}</td></tr>
{foreach key=num item=referral from=$referrals}
<tr class="clientareatableactive"><td>{$referral.date}</td><td>{$referral.service}</td><td>{$referral.amount}</td><td>{$referral.billingcycle}</td><td>{$referral.commission}</td><td>{$referral.status}</td></tr>
{foreachelse}
<tr class="clientareatableactive"><td colspan="6">{$LANG.affiliatesnosignups}</td></tr>
{/foreach}
</table>

{if $affiliatelinkscode}
<p><strong>{$LANG.affiliateslinktous}</strong></p>
<p align="center">{$affiliatelinkscode}</p>
{/if}
<hr>
<!-- BEGIN AFFILIATE COUPONS CODE -->
{php}
include('affcoupons.php');
{/php}
<!-- END AFFILIATE COUPONS CODE -->
{/if}
