{if $inactive}
{include file="$template/pageheader.tpl" title=$LANG.affiliatestitle}
<div class="alert alert-warning">
    <p>{$LANG.affiliatesdisabled}</p>
</div>
<br />
<br />
<br />
{else}
{include file="$template/pageheader.tpl" title=$LANG.affiliatestitle desc=$LANG.affiliatesrealtime}

<div class="textcenter">
    <h3>{$LANG.affiliatesreferallink}</h3>
    <input type="text" value="{$referrallink}" class="bigfield fullwidth textcenter" />
</div>

<div class="row">
<div class="affiliatestatcontainer">
    <div class="affiliatestatblock">
        {$LANG.affiliatesvisitorsreferred}<br />
        <span class="stat">{$visitors}</span>
    </div>
    <div class="affiliatestatblock">
        {$LANG.affiliatessignups}<br />
        <span class="stat">{$signups}</span>
    </div>
    <div class="affiliatestatblock">
        {$LANG.affiliatesconversionrate}<br />
        <span class="stat">{$conversionrate}%</span>
    </div>
</div>
</div>


<table class="table table-striped table-framed halfwidthcontainer">
    <tr>
      <td class="left">{$LANG.affiliatescommissionspending}:</td>
      <td><strong>{$pendingcommissions}</strong></td>
    </tr>
    <tr>
      <td class="left">{$LANG.affiliatescommissionsavailable}:</td>
      <td><strong>{$balance}</strong></td>
    </tr>
    <tr>
      <td class="left">{$LANG.affiliateswithdrawn}:</td>
      <td><strong>{$withdrawn}</strong></td>
    </tr>
</table>

<br />

{if $withdrawrequestsent}
<div class="alert alert-success">
    <p>{$LANG.affiliateswithdrawalrequestsuccessful}</p>
</div>
{else}
{if $withdrawlevel}
<p align="center">
  <input type="button" class="btn btn-large btn-primary" value="{$LANG.affiliatesrequestwithdrawal}" onclick="window.location='{$smarty.server.PHP_SELF}?action=withdrawrequest'" />
</p>
{/if}
{/if}

{include file="$template/subheader.tpl" title=$LANG.affiliatesreferals}

<p>{$numitems} {$LANG.recordsfound}, {$LANG.page} {$pagenumber} {$LANG.pageof} {$totalpages}</p>

<table class="table table-striped table-framed">
    <thead>
        <tr>
            <th{if $orderby eq "date"} class="headerSort{$sort}"{/if}><a href="affiliates.php?orderby=date">{$LANG.affiliatessignupdate}</a></th>
            <th{if $orderby eq "product"} class="headerSort{$sort}"{/if}><a href="affiliates.php?orderby=product">{$LANG.orderproduct}</a></th>
            <th{if $orderby eq "amount"} class="headerSort{$sort}"{/if}><a href="affiliates.php?orderby=amount">{$LANG.affiliatesamount}</a></th>
            <th>{$LANG.affiliatescommission}</th>
            <th{if $orderby eq "status"} class="headerSort{$sort}"{/if}><a href="affiliates.php?orderby=status">{$LANG.affiliatesstatus}</a></th>
        </tr>
    </thead>
    <tbody>
  {foreach key=num item=referral from=$referrals}
  <tr>
    <td>{$referral.date}</td>
    <td>{$referral.service}</td>
    <td>{$referral.amountdesc}</td>
    <td>{$referral.commission}</td>
    <td>{$referral.status}</td>
  </tr>
{foreachelse}
        <tr>
            <td colspan="5">{$LANG.norecordsfound}</td>
        </tr>
{/foreach}
    </tbody>
</table>

<div class="pagination">
    <ul>
        <li class="prev{if !$prevpage} disabled{/if}"><a href="{if $prevpage}affiliates.php?page={$prevpage}{else}javascript:return false;{/if}">&larr; {$LANG.previouspage}</a></li>
        <li class="next{if !$nextpage} disabled{/if}"><a href="{if $nextpage}affiliates.php?page={$nextpage}{else}javascript:return false;{/if}">{$LANG.nextpage} &rarr;</a></li>
    </ul>
</div>

{if $affiliatelinkscode}
{include file="$template/subheader.tpl" title=$LANG.affiliateslinktous}
<div class="textcenter">
    {$affiliatelinkscode}
</div>
{/if}

<!-- BEGIN AFFILIATE COUPONS CODE -->
{php}
include('affcoupons.php');
{/php}
<!-- END AFFILIATE COUPONS CODE -->

{/if}
