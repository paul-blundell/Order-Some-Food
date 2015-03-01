	<form method="post" action="<?=base_url('order/placeorder')?>" id="detailsForm">
	<input type="hidden" name="takeawayId" value="<?=$takeaway->takeawayId?>"/>
	<input type="hidden" name="menuUrl" value="<?=$menuUrl?>"/>
	<div id="additional-comments">
	    <h3><?=$this->lang->line('comments');?></h3>
	    <p><?=$this->lang->line('allergic');?></p>
	    <textarea name="comments" rows="10" cols="30"></textarea>
	</div>
	<div id="checkout">
	    
	  	<h3><?=$this->lang->line('delivery');?></h3><br/>
		<select id="delivery-method" class="type bigbox" name="delivery">
		    <option value="2"><?=$this->lang->line('cash');?></option>
		    <option value="1"><?=$this->lang->line('collect');?></option>
		</select>
		<br/><br/>
		<label><?=$this->lang->line('name');?></label><input type="text" name="delivery_name" value="<?=$user['delivery_name']?>"/>
		<div id="delivery-details">
			<label><?=$this->lang->line('address1');?></label><input type="text" name="address1" value="<?=$user['address1']?>"/>
			<label><?=$this->lang->line('address2');?></label><input type="text" name="address2" value="<?=$user['address2']?>"/>
			<label><?=$this->lang->line('town');?></label><input type="text" name="town" value="<?=$user['town']?>"/>
			<label><?=$this->lang->line('postcode');?></label><input type="text" name="postcode" value="<?=$user['postcode']?>"/>
			<label><?=$this->lang->line('phone');?></label><input type="text" name="telephone" value="<?=$user['telephone']?>"/><br/><br/>    
		</div>
		<?php if(!$user['loggedIn']): ?>
		<input type="checkbox" class="register-box" name="register" <?=(isset($user['register'])) ? "checked='checked'" : ""?>/> <span class="small">Register to remember details and orders</span><br/><br/>
		<div class="register">
		    <label><?=$this->lang->line('email');?></label><input type="text" name="email" value="<?=(isset($user['register'])) ? $user['email'] : ""?>"/>
		    <label><?=$this->lang->line('pass');?></label><input type="password" name="pass" />
		    <label><?=$this->lang->line('conf-pass');?></label><input type="password" name="pass-confirm" />
		    <?php if (!$register): ?>
		    <span class="warning"><?=$this->lang->line('invalid');?></span>
		    <?php endif; ?>
		</div>
		<?php endif; ?>  
	    
	    <input type="hidden" name="uid" value="<?=$user['uid']?>" />
	    <input type="hidden" name="takeaway" value="<?=$takeaway->takeawayId?>" />
	    <input type="hidden" name="returnurl" value="<?=base_url('order/takeaway/'.$takeaway->takeawayId)?>" />

	    <?php if ($takeaway->paypalActive): ?>
	    <input class="button" type="submit" name="submitorder" id="proceed" value="<?=$this->lang->line('proceedorder');?>" />
	    <?php else: ?>
	    <input class="button" type="submit" name="submitorder" id="placeorder" value="<?=$this->lang->line('placeorder');?>" />
	    <?php endif; ?>
	    <span id="loading"></span>
	</div>
	</form>
