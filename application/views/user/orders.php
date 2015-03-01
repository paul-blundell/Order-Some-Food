<?php if ($orders):?>
    <table id="previous-orders">
        <tr><th><?=$this->lang->line('date-ordered');?></th><th><?=$this->lang->line('ordered-from');?></th><th><?=$this->lang->line('items-ordered');?></th><th><?=$this->lang->line('total-cost');?><th></tr>
        <?php foreach ($orders AS $order):?>
            <tr>
                <td><?=$order->date?></td>
                <td><a href="<?=base_url('menu/takeaway/'.$order->takeawayId)?>"><?=$order->name?></a></td>
                <td>
                <?php $priceAtOrder = 0; ?>
                <?php foreach($this->orders->getItemsInOrder($order->orderId) AS $item): ?>
                    <?php if ($parent = $this->orders->hasParent($item->menuId)): ?>
                        <?=$parent->name?> - 
                    <?php endif; ?>
                    <?=$item->name?> x <?=$item->qty?><br/>
                    <?php $priceAtOrder += $item->priceAtOrder; ?>
                <?php endforeach; ?>
                </td>
                <td><?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($priceAtOrder)); ?><?= $this->config->item('price_suffix'); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
<p><?=$this->lang->line('no-orders');?></p>
<?php endif; ?>