<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!--
Design by Free CSS Templates
http://www.freecsstemplates.org
Released for free under a Creative Commons Attribution 2.5 License

Name       : Plain & Clean
   
Description: A two-column, fixed-width design with dark color scheme.
Version    : 1.0
Released   : 20111024

-->
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="refresh" content="20">
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title>Order Some Food - <?=$this->lang->line('incoming-orders');?></title>
<base href="<?= base_url(); ?>" />
<link href="<?= base_url(); ?>css/orders.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="<?= base_url(); ?>js/jquery.js"></script>
</head>
<body>
<?php if(!$open): ?>
<div id="closed">
    <img src="<?=base_url()?>images/closed.png" alt="Closed" />
</div>
<?php endif; ?>
<div id="top">
    <h1 class="logo">Order Some Food <span><?=$this->lang->line('takeaway-orders');?></span></h1>
    <div class="status right">
        <?php if(!$open): ?>
        <a href="<?=base_url('takeaway/orders/open')?>"><?=$this->lang->line('open-takeaway');?></a>
        <?php else: ?>
        <a href="<?=base_url('takeaway/orders/close')?>"><?=$this->lang->line('close-takeaway');?></a>
        <?php endif; ?>
    </div>
</div>

<div id="processing-orders">
    <?php if ($orders): ?>
    <?php foreach($orders AS $order): ?>
    <?php
        $toTime = strtotime(date("Y-m-d H:i:s", time()));
        $fromTime = strtotime($order->date);
        $waitingTime = round(abs($toTime - $fromTime) / 60,2);
        if ($waitingTime > 30 && $waitingTime <= 60)
            $class = 'yellow';
        else if ($waitingTime > 60)
            $class = 'red';
    ?>
    <div class="order">
        <?php if ($waitingTime > 30 && $waitingTime <= 60): ?>
        <div class="yellow">
        <?=$this->lang->line('order-wait-30');?>
        </div>
        <?php elseif ($waitingTime > 60): ?>
        <div class="red">
        <?=$this->lang->line('order-wait-60');?>
        </div>
        <?php endif; ?>
        <div class="items">
            <?php $priceAtOrder = 0; ?>
            <?php foreach($this->orders->getItemsInOrder($order->orderId) AS $item): ?>
                    <?php if ($parent = $this->orders->hasParent($item->menuId)): ?>
                        <?=$parent->name?> - 
                    <?php endif; ?>
                    <?=$item->name?> x <?=$item->qty?><br/>
                    <?php $priceAtOrder += ($item->priceAtOrder*$item->qty); ?>
            <?php endforeach; ?>
        </div>
        <?php if ($order->additional):?>
        <div class="notes">
            <span><?=$this->lang->line('notes');?></span>
            <?=$order->additional?>
        </div>
        <?php endif; ?>
        <div class="address">
            <?=$order->delivery_name?><br/>
            <?=$order->address1?><br/>
            <?=($order->address2 != '') ? $order->address2.'<br/>' : ''?>
            <?=$order->town?><br/>
            <?=$order->postcode?><br/>
            <?=$order->phone?>
        </div>
        <div class="total">
            <div class="left">
            <?=$this->lang->line('total');?>: <strong><?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($priceAtOrder)); ?><?= $this->config->item('price_suffix'); ?></strong><br/>
            </div>
            <div class="right rightalign">
                <?php if ($order->deliveryType == 1): ?>
                <span class="large"><?=$this->lang->line('collection');?></span>
                <?php elseif ($order->deliveryType == 2): ?>
                <span class="large"><?=$this->lang->line('delivery');?></span>
                <?php endif; ?>
		<?php if ($order->paid == 1): ?>
		<br/><?=$this->lang->line('paid');?>
		<?php else: ?>
                <br/><?=$this->lang->line('not-paid');?>
		<?php endif; ?>
            </div>
            <div class="clear"></div>
        </div>
        <div class="actions">
            <div class="left">
                <a href="<?=base_url('takeaway/orders/complete/'.$order->orderId)?>" title="Mark Order as Completed"><img src="<?=base_url()?>images/tick.png" alt="Complete" /> <?=$this->lang->line('complete-order');?></a>
            </div>
            <div class="right">
                <a href="javascript:void()" onclick="cancel(<?=$order->orderId?>)" title="Cancel Order"><img src="<?=base_url()?>images/cross.png" alt="Cancel Order" /></a>
            </div>
            <div class="clear"></div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php else: ?>
    <p><?=$this->lang->line('no-pending');?></p>
    <p><?=$this->lang->line('refresh-page');?></p>
    <?php endif; ?>

</div>
<script type="text/javascript">
function cancel(id)
{
        var answer = confirm("<?=$this->lang->line('cancel-warning');?>");
        if (answer){
                window.location = "<?=base_url('takeaway/orders/cancel')?>/"+id;
        }
}
</script>
</body>
</html>
