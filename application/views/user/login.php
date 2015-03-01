<h2><?=$this->lang->line('login');?></h2>
<p></p>
<div id="login">
<form method="post" action="<?=base_url('user/login')?>">
<label><?=$this->lang->line('email');?></label><br/><input type="text" name="email" /><br/>
<label><?=$this->lang->line('pass');?></label><br/><input type="password" name="pass" /><br/>
<input type="hidden" name="confirmurl" value="<?=base_url('user/account')?>" />
<input type="hidden" name="failedurl" value="<?=base_url('users/login')?>" />
<input class="button" type="submit" name="login" value="<?=$this->lang->line('login');?>" />
</form>
<p><?=$this->lang->line('forgot');?></p>
</div>