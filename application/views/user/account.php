<h2><?=$this->lang->line('login-details');?></h2>
<?php if (count($failures) > 0): ?>
<p>
    <?php foreach($failures AS $field=>$message): ?>
        <span class="red"><?=$message?></span><br/>
    <?php endforeach; ?>
</p>
<?php endif; ?>
<?php if (isset($_GET['done'])): ?>
<?=$this->lang->line('accountupdated');?><br/><br/>
<?php endif; ?>
<div id="login">
<form method="post" action="<?=base_url('user/account')?>">
<input type="hidden" name="orig-email" value="<?=$email?>" />
<div class="left">
<label><?=$this->lang->line('email');?></label><br/><input type="text" name="email" value="<?=$email?>"/>
</div>
<?php if (!$this->user->isAdminSwitched()): ?>
<div>
<label><?=$this->lang->line('current-pass');?></label><br/><input type="password" name="pass" />
</div>
<?php endif; ?>
<div class="clear"></div>
<div class="left">
<label><?=$this->lang->line('new-pass');?></label><br/><input type="password" name="newpass" />
</div>
<div>
<label><?=$this->lang->line('conf-pass');?></label><br/><input type="password" name="newpass-confirm" /><br/>
</div>
<input class="button" type="submit" name="update" value="<?=$this->lang->line('update-details');?>" />
</form>
</div>
