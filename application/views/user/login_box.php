<?php if (!$this->user->isLoggedIn()): ?>
<form method="post" action="<?=base_url('user/login');?>">
<input type="text" name="email" value="" title="Email Address" class="textbox" />
<input type="password" name="pass" value="" title="Password" class="textbox" />
<input type="hidden" name="confirmurl" value="<?=base_url('user/orders')?>"/>
<input type="hidden" name="failedurl" value="<?=base_url(uri_string())?>" />
<input type="submit" name="submit" value="Login" /><br/>
    <?php if (isset($_GET['login'])): ?>
    <span class="warning"><?=$this->lang->line('invalid-login');?></span>
    <?php endif; ?>
</form>
<?php else: ?>
<ul>
        <li class="first"><a href="<?=base_url('user/account')?>"><?=$this->lang->line('myaccount');?></a></li>
        <?php if ($this->user->isTakeawayOwner()): ?>
                <li><a href="<?=base_url('takeaway/orders')?>"><?=$this->lang->line('vieworders');?></a></li>
                <li><a href="<?=base_url('takeaway/menu')?>"><?=$this->lang->line('editmenu');?></a></li>
        <?php else: ?>
                <li><a href="<?=base_url('user/orders')?>"><?=$this->lang->line('vieworders');?></a></li>
        <?php endif; ?>
        <?php if ($this->user->isAdmin()): ?>
                <li><a href="<?=base_url('admin/awaiting')?>"><?=$this->lang->line('admin');?></a></li>
        <?php endif; ?>
        <li><a href="<?=base_url('user/logout')?>"><?=$this->lang->line('logout');?></a></li>
</ul>
        <?php if ($this->user->isTakeawayOwner() && count($this->user->getTakeaways()) > 1): ?>
        <div id="switchtakeaway" class="clear">
                <form method="POST" action="<?=base_url('takeaway/set')?>">
                <label><?=$this->lang->line('selecttakeaway');?></label> <select name="takeaway">
                        <?php foreach($this->user->getTakeaways() AS $takeaway): ?>
                        <option value="<?=$takeaway->takeawayId?>" <?=($this->user->getTakeawayId() == $takeaway->takeawayId) ? 'selected="selected"' : ''?>><?=$takeaway->name?></option>
                        <?php endforeach; ?>
                </select>
                <input type="submit" name="submit" value="Switch" />
                </form>
        </div>
        <?php endif; ?>
<?php endif; ?>