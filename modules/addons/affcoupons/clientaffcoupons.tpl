<div id="affcoupons-ajax">

    <div class="page-header">
        <div class="styled_title">
            <h2>Landing Page</h2>
        </div>
    </div>
    <div class="affnotice">
        <div id="notice" class="alert alert-{if !$notice_type}success{else}{$notice_type}{/if}" {if !$notice}style="display: none;"{/if}>{$notice}</div>
    </div>
    <form method="POST" action="index.php?m=affcoupons" name="landingpage" id="landingpageForm">
        <input type="hidden" name="cmd" value="modlanding">
        <div class="well textcenter">
            <input type="text" name="landing" id="landing" value="{$landing}" class="bigfield">
            <div class="internalpaadding">
                <p>This option will control where your referrals will be redirected after visiting your referral link.</p>
                <input type="submit" name="Submit" value="Update" class="btn btn-primary btn-large" id="updatelanding">
            </div>
        </div>
    </form>
	<div class="page-header">
	    <div class="styled_title">
	        <h2>Your Coupons</h2>
	    </div>
	</div>
	<table class="table table-striped table-framed">
	    <thead>
	        <tr>
	            <th>&nbsp;</th>
	            <th>Coupon Code</th>
	            <th>Coupon Type</th>
	            <th>Coupon Value</th>
	            <th>Uses</th>
	        </tr>
	    </thead>

	    {if !$coupon}
			<tbody><tr><td colspan="5" style="text-align: center; font-weight: bold;">No Coupons Found</td></tr></tbody>
	    {else}
		    <tbody>
				{foreach from=$coupon key=k item=v}
					<tr>
						<td>
							<a href="index.php?m=affcoupons&cmd=del&cid={$v.id}"><img src="modules/addons/affcoupons/inc/images/delete.png" alt="Delete"></a>
						</td>
						<td>
							{$v.code}
						</td>
						<td>
							{$v.type}
						</td>
						<td>
							{$v.value}
						</td>
						<td>
							{$v.uses}
						</td>
					</tr>
				{/foreach}
		    </tbody>
	    {/if}

	</table>
	<div class="page-header">
	    <div class="styled_title">
	        <h2>Add Coupons</h2>
	    </div>
	</div>
	<form action="index.php?m=affcoupons" method="POST" name="addcoupons" class="form-horizontal">
	    <input type="hidden" name="cmd" value="add">
	    <div class="well">
	        <div class="control-group">
	            <label for="code" class="control-label">Coupon Code:</label>
	            <div class="controls">
	                <input type="text" name="code" id="code">
	            </div>
	        </div>
	        <div class="control-group">
	            <label for="code" class="control-label">Coupon Type:</label>
	            <div class="controls">
	                <select name="type">
						{foreach from=$avail_coupon item=v}
							<option value="{$v.enc_string}">{$v.label}</option>
						{/foreach}
	                </select>
	            </div>
	        </div>
	        <div class="control-group">
	            <div class="controls">
	                <input type="submit" name="Submit" value="Add" class="btn btn-primary btn-large">
	            </div>
	        </div>
	    </div>
	</form>


</div>
<!-- end affcoupons-ajax -->