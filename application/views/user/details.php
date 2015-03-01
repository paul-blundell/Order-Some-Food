<?php $this->load->view('user/header'); ?>
<p class="warning"><?=(isset($error)) ? $error : '';?></p>
<form method="post" action="" enctype="multipart/form-data">
<h3><?=$this->lang->line('logo');?></h3>
<img class="logo-thumb" src="<?=$logo?>?<?=time()?>" alt="No logo available" /><br/><input type="file" name="logo" />
<p><br/></p>
<h3><?=$this->lang->line('description');?></h3>
<textarea name="description" cols="35" rows="4"><?=$takeaway->description?></textarea>
<p><br/></p>
<h3><?=$this->lang->line('address');?></h3>
<textarea name="address" cols="35" rows="4"><?=$takeaway->address?></textarea>
<p></p>
<label class="leftlabel"><?=$this->lang->line('postcode');?></label><input type="text" name="postcode" value="<?=$takeaway->postcode?>"/>
<p><br/></p>
<h3><?=$this->lang->line('opening-time');?></h3>
<?php
$days = array('sun' => 'Sunday','mon'=>'Monday','tue'=>'Tuesday','wed'=>'Wednesday','thu'=>'Thursday','fri'=>'Friday','sat'=>'Saturday');

foreach ($days AS $code=>$day): ?>
<label class="leftlabel"><?=$this->lang->line($code);?></label>
<select name="day[<?=$code?>][]">
<?php for ($i=0; $i<24; $i++): ?>
	<?php if (isset($openingtimes[$code]) && sprintf('%02d', $i).":00:00" == $openingtimes[$code]['open']): ?>
			<option selected="selected"><?=sprintf('%02d', $i)?>:00</option>
	<?php else: ?>
			<option><?=sprintf('%02d', $i)?>:00</option>
	<?php endif; ?>
<?php endfor; ?>
</select> - 
<select name="day[<?=$code?>][]">
<?php for ($i=0; $i<24; $i++): ?>
	<?php if (isset($openingtimes[$code]) && sprintf('%02d', $i).":00:00" == $openingtimes[$code]['close']): ?>
			<option selected="selected"><?=sprintf('%02d', $i)?>:00</option>
	<?php else: ?>
			<option><?=sprintf('%02d', $i)?>:00</option>
	<?php endif; ?>
<?php endfor; ?>
</select> 
<input type="checkbox" name="day[<?=$code?>][]" <?= (!isset($openingtimes[$code])) ? 'checked="checked"' : ''; ?>/> Closed<br/>
<?php endforeach;?>
<p><br/></p>
<h3><?=$this->lang->line('settings');?></h3>
<label class="leftlabel"><?=$this->lang->line('delivery-charge');?></label><input type="text" name="delivery" size="4" value="<?=$takeaway->deliveryCharge?>"/><br/>
<label class="leftlabel"><?=$this->lang->line('delivery-time');?></label><input type="text" name="delivery-time" size="2" value="<?=$takeaway->deliveryTime?>" />
<div class="clear"></div>
<p><br/></p>
<h3><?=$this->lang->line('onlinepayments');?></h3>
<label class="leftlabel"><?=$this->lang->line('paypal');?></label><select name="paypal"><option value="1" <?=($takeaway->paypalActive)? 'selected="selected"' : ''?>>Yes</option><option value="0" <?=(!$takeaway->paypalActive)? 'selected="selected"' : ''?>>No</option></select><br/>
<div id="paypal">
<label class="leftlabel"><?=$this->lang->line('paypal-email');?></label><input type="text" name="paypal-email" value="<?=$takeaway->paypalEmail?>" /><br/>
<label class="leftlabel"><?=$this->lang->line('paypal-signature');?></label><input type="text" name="paypal-signature" value="<?=$takeaway->paypalSignature?>" /><br/>
<label class="leftlabel"><?=$this->lang->line('paypal-password');?></label><input type="text" name="paypal-password" value="<?=$takeaway->paypalPassword?>" /><br/>
<a href="javascript:void()" onclick="javascript:window.open('https://www.paypal.com/us/cgi-bin/webscr?cmd=_login-api-run', 'apiwizard','toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=yes, ,left=100, top=100, width=380, height=470'); return false;" class="scalable" type="button" id="payment_express_checkout_required_express_checkout_api_wizard">
<?=$this->lang->line('get-paypal');?>
</a>
</div>
<div class="clear"></div>
<input type="submit" name="submit" value="<?=$this->lang->line('update');?>" />
</form>
