<div id="affcoupons-wrap container">
	<div id="affcoupons">
		<div class="row ac-title">
			<div class="col-md-12">
				<h2>Landing Page</h2>
		    </div>
		</div>
		<div class="row">
		    <div class="affnotice col-md-12">
		        <div id="notice" class="alert alert-{if !$notice_type}success{else}{$notice_type}{/if}" {if !$notice}style="display: none;"{/if}>{$notice}</div>
		    </div>
		</div>
		<div class="row">
			<form method="POST" action="{$index_page}?m=affcoupons" name="landingpage" id="landingpageForm" class="col-md-12">
		        <input type="hidden" name="cmd" value="modlanding"/>
		        <div class="well textcenter">
		            <input type="text" name="landing" id="landing" value="{$landing}" class="bigfield"/>
		            <div class="internalpaadding">
		                <p>This option will control where your referrals will be redirected after visiting your referral link.</p>
		                <input type="submit" name="Submit" value="Update" class="btn btn-primary btn-large" id="updatelanding"/>
		            </div>
		        </div>
		    </form>
		</div>
		<div class="row ac-title">
			<div class="col-md-12">
				<h2>Your Coupons</h2>
			</div>
		</div>
		<div class="row">
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
				<tbody>
				{if !$coupon}
					<tr>
						<td colspan="5" style="text-align: center; font-weight: bold;">No Coupons Found</td>
					</tr>


											{else}
					{foreach from=$coupon key=k item=v}
					<tr>
							<td>
								<a href="{$index_page}?m=affcoupons&cmd=del&cid={$v.id}"><img src="modules/addons/affcoupons/inc/images/delete.png" alt="Delete"></a>
							</td>
							<td>
								{$v.code}
							</td>
							<td>
								{$v.type}
							</td>
							<td>
							{if $v.type == "Percentage"}
								{$v.value|string_format:"%d"}%
							{else}
								{$v.value}
							{/if}
							</td>
							<td>
								{$v.uses}
							</td>
						</tr>
				{/foreach}
				{/if}
				</tbody>
			</table>
		</div>
		<div class="row">
			<div class="page-header">
			    <div class="styled_title">
			        <h2>Add Coupons</h2>
			    </div>
			</div>
			<form action="{$index_page}?m=affcoupons" method="POST" name="addcoupons" class="form-horizontal" role="form">
			    <input type="hidden" name="cmd" value="add"/>
			    <div class="well">
			        <div class="form-group">
			            <label for="couponCode" class="control-label col-sm-2">Coupon Code:</label>
			            <div class="col-sm-10">
			                <input type="text" name="code" id="couponCode"/>
			            </div>
			        </div>
			        <div class="form-group">
			            <label for="couponType" class="control-label col-sm-2">Coupon Type:</label>
			            <div class="col-sm-10">
			                <select name="type" id="couponType">
								{foreach from=$avail_coupon item=v}
									<option value="{$v.id}">{$v.label}</option>
								{/foreach}
			                </select>
			            </div>
			        </div>
			        <div class="form-group">
			            <div class="col-sm-offset-2 col-sm-10">
			                <input type="submit" name="Submit" value="Add" class="btn btn-primary btn-large"/>
			            </div>
			        </div>
			    </div>
			</form>
		</div>
	</div>
</div>
