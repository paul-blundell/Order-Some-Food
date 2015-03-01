<div id="checkout-login">
    <h4><?=$this->lang->line('existing');?></h4>
    <div id="existing-member">
        <form method="post" action="<?=base_url('users/login')?>">
        <label><?=$this->lang->line('email');?></label><input type="text" name="email" />
        <label><?=$this->lang->line('pass');?></label><input type="password" name="pass" />
        <input type="hidden" name="confirmurl" value="<?=base_url('order/takeaway/'.$takeaway->takeawayId)?>" />
        <input type="hidden" name="failedurl" value="<?=base_url('order/takeaway/'.$takeaway->takeawayId)?>" />
        <input type="submit" name="login" value="<?=$this->lang->line('login');?>" />
        </form>
    </div>
</div>