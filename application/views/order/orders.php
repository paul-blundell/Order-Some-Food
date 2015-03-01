<?php if ($this->user->isTakeawayOwner()) $this->load->view('user/header'); ?>

<div class="left half">
    <p class="small"><?=$this->lang->line('view-pending-pre');?><br/><br/></p>
    <div class="large spacing">
        <a href="javascript:void(0)" onclick="javascript:window.open('<?=base_url('takeaway/orders/processing')?>', '', 'toolbar=no, location=0, directories=no, status=no, menubar=0, scrollbars=1, resizable=yes, width=950, height=650');"><?=$this->lang->line('view-pending');?></a>
    </div>
    <p class="spacing"><?=$this->lang->line('num-completed');?>: <span class="large"><?=$numberOfOrders?></span></p>
    <p class="small"><?=$this->lang->line('num-cancelled');?>: <span class="large"><?=$numberOfCancelledOrders?></span></p>
    <p class="spacing"><?=$this->lang->line('value-orders');?>: <span class="large"><?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($valueOfOrders->value))?><?= $this->config->item('price_suffix'); ?></span></p>
</div>
<div class="right">
    <?php if ($graph): ?>
    <div class="center small">
    <img src="<?=$graph?>" alt="Order graph" /><br/>
    <?=$this->lang->line('daily-orders');?>
    </div>
    <?php endif; ?>
</div>
<div class="clear"></div>
<p><br/></p>
<div class="left">
<h3><?=$this->lang->line('completed-orders');?></h3>
<br/>
<?php if ($completedOrders): ?>
<table id="ordersdatatable" class="half">
    <thead><tr><th><?=$this->lang->line('date-ordered');?></th><th><?=$this->lang->line('items');?></th><th><?=$this->lang->line('total');?></th></tr></thead>
    <tbody>
    <?php foreach ($completedOrders AS $order): ?>
    	<tr><td><?=$order->date?></td><td class="smaller">
        <?php
        $total = 0;
        foreach ($this->orders->getItemsInOrder($order->orderId) AS $item): ?>
        	<?php if ($parent = $this->orders->hasParent($item->menuId)): ?>
                <?=$parent->name?> - 
        	<?php endif; ?>
        	<?=$item->name?> x <?=$item->qty?><br/>
            	<?php $total += $item->priceAtOrder*$item->qty; ?>
        <?php endforeach; ?>
        </td><td><?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($total))?><?= $this->config->item('price_suffix'); ?></td></tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p><?=$this->lang->line('no-orders');?></p>
<?php endif; ?>
</div>
<div class="right">
<h3><?=$this->lang->line('popular-products');?></h3>
<br/>
<?php if ($popularProducts): ?>
<table id="populardatatable" class="half">
    <thead><tr><th><?=$this->lang->line('product-name');?></th><th><?=$this->lang->line('num-orders');?></th><th><?=$this->lang->line('avg-qty');?></th></tr></thead>
    <tbody>
    <?php foreach ($popularProducts AS $product): ?>
        <tr><td>
        <?php if ($parent = $this->orders->hasParent($product->menuId)): ?>
                <?=$parent->name?> - 
        <?php endif; ?>
        <?=$product->name?></td><td><?=$product->count?></td><td><?=round($product->quantity)?></td></tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php else: ?>
<p><?=$this->lang->line('no-orders');?></p>
<?php endif; ?>
</div>