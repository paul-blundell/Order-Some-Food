<p><?=$this->lang->line('takeawayregistertext');?>:</p>
<p><br/></p>
<?php if (count($failures) > 0): ?>
<p>
    <?php foreach($failures AS $field=>$message): ?>
        <span class="red"><?=$message?></span><br/>
    <?php endforeach; ?>
</p>
<?php endif; ?>
<form method="POST" action="">
    <label class="leftlabel <?=(isset($failures['takeawayname'])) ? 'red' : ''?>"><?=$this->lang->line('businessname');?> <span class="red">*</span></label><input type="text" name="takeawayname" value="<?=(isset($_POST['takeawayname'])) ? $_POST['takeawayname'] : '' ?>"/><br/>
    <label class="leftlabel <?=(isset($failures['address1'])) ? 'red' : ''?>"><?=$this->lang->line('address1');?> <span class="red">*</span></label><input type="text" name="address1" value="<?=(isset($_POST['address1'])) ? $_POST['address1'] : '' ?>"/><br/>
    <label class="leftlabel"><?=$this->lang->line('address2');?></label><input type="text" name="address2" value="<?=(isset($_POST['address2'])) ? $_POST['address2'] : '' ?>"/><br/>
    <label class="leftlabel <?=(isset($failures['town'])) ? 'red' : ''?>"><?=$this->lang->line('town');?> <span class="red">*</span></label><input type="text" name="town" value="<?=(isset($_POST['town'])) ? $_POST['town'] : '' ?>"/><br/>
    <label class="leftlabel <?=(isset($failures['postcode'])) ? 'red' : ''?>"><?=$this->lang->line('postcode');?><span class="red">*</span></label><input type="text" name="postcode" value="<?=(isset($_POST['postcode'])) ? $_POST['postcode'] : '' ?>"/><br/>
    <label class="leftlabel"><?=$this->lang->line('phone');?></label><input type="text" name="phone" value="<?=(isset($_POST['phone'])) ? $_POST['phone'] : '' ?>"/><br/>
    <p><?=$this->lang->line('shortnametext');?>:<br/>
    <label class="leftlabel <?=(isset($failures['shortname'])) ? 'red' : ''?>"><?=$this->lang->line('shortname');?> <span class="red">*</span></label><input type="text" id="shortname" name="shortname" value="<?=(isset($_POST['shortname'])) ? $_POST['shortname'] : '' ?>"/> <a href="javascript:void()" onclick="checkAvailability()">Check Availability</a><br/>
    <label class="leftlabel">&nbsp;</label><div id="shortnameresponse">&nbsp;</div><br/>
    <p><?=$this->lang->line('description');?></p>
    <textarea name="description" cols="50" rows="5"><?=(isset($_POST['description'])) ? $_POST['description'] : '' ?></textarea><br/>
    <label class="leftlabel"><?=$this->lang->line('deliverycharge');?></label><input type="text" name="deliveryCharge" value="<?=(isset($_POST['deliveryCharge'])) ? $_POST['deliveryCharge'] : '' ?>"/><br/>
    <label class="leftlabel"><?=$this->lang->line('deliverytime');?></label><input type="text" name="deliveryTime" value="<?=(isset($_POST['deliveryTime'])) ? $_POST['deliveryTime'] : '' ?>"/><br/>
    <label class="leftlabel"><?=$this->lang->line('category');?>:</label><select name="category">
        <?php foreach($categories AS $id=>$name): ?>
        <option value="<?=$id?>" <?=(isset($_POST['category']) && $_POST['category'] == $id) ? 'selected="selected"' : '' ?>><?=$name?></option>
        <?php endforeach; ?>
    </select><br/><br/>
    <?php if ($this->user->isLoggedIn()): ?>
    <p><br/></p>
    <p><?=$this->lang->line('useraccount');?>:</p>
    <p><strong><?= $this->user->getUid() ?> : <?=$this->user->getEmail() ?></strong></p>
    <?php else: ?>
    <?php //Choose to login or to register ?>
    <div id="register">
    <p><?=$this->lang->line('registertext');?>:</p><br/>
    <label class="leftlabel <?=(isset($failures['regemail'])) ? 'red' : ''?>"><?=$this->lang->line('emailaddress');?> <span class="red">*</span></label><input type="text" name="regemail" value="<?=(isset($_POST['regemail'])) ? $_POST['regemail'] : '' ?>"/><br/>
    <label class="leftlabel"><?=$this->lang->line('password');?> <span class="red">*</span></label><input type="password" name="regpassword" /><br/>
    <label class="leftlabel"><?=$this->lang->line('confpassword');?> <span class="red">*</span></label><input type="password" name="regconfirmpassword" /><br/>
    <a href="javascript:void()" onclick="showLogin()"><?=$this->lang->line('alreadyregistered');?></a>
    </div>
    <div id="login">
    <p>Please enter your login details below:</p><br/>
    <label class="leftlabel <?=(isset($failures['email'])) ? 'red' : ''?>"><?=$this->lang->line('email');?> <span class="red">*</span></label><input type="text" name="email" value="<?=(isset($_POST['email'])) ? $_POST['email'] : '' ?>"/><br/>
    <label class="leftlabel"><?=$this->lang->line('password');?> <span class="red">*</span></label><input type="password" name="password" /><br/>
    <a href="javascript:void()" onclick="showRegister()"><?=$this->lang->line('notamember');?></a>
    </div>
    <?php endif; ?>
    <p><br/></p>
    <input type="submit" name="submit" value="<?=$this->lang->line('submit');?>" />
</form>