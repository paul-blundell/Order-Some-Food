<?php $this->load->view('takeaway/header'); ?>
<div id="order-wrapper">
<h3><?=$this->lang->line('yourorder');?></h3>
<div id="order-list">
    <?php foreach ($order AS $id=>$items): ?>
    <div class="item">
    <div class="name">
        <?=$items['name']?> x <?=$items['qty']?>
    </div>
    <div class="styledbutton"><a href="javascript:void()" onclick="removeItem(<?=$takeaway->takeawayId?>,<?=$items['id']?>)" class="large">-</a></div>
    <div class="price">
        <?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($items['price']*$items['qty']))?><?= $this->config->item('price_suffix'); ?>
    </div>
    <div class="clear"></div>
    </div>
    <?php endforeach; ?>
    <div class="delivery">
       <?=$this->lang->line('delivery');?> <span class="price"><?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($takeaway->deliveryCharge))?><?= $this->config->item('price_suffix'); ?></span>
    </div>
    <div class="total">
       <?=$this->lang->line('finaltotal');?> <span class="price"><strong><?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($total))?><?= $this->config->item('price_suffix'); ?></strong></span>
    </div>
</div>
<div id="checkout-wrapper">
<?php if(!$user['loggedIn']): ?>
    <?php $this->load->view('order/login'); ?>
<?php endif; ?>

<?php $this->load->view('order/checkout'); ?>
<div class="clear"></div>
</div>
</div>
