<?php foreach ($order AS $id=>$items): ?>
<div class="item">
    <div class="name">
        <?=$items['name']?> x <?=$items['qty']?>
    </div>
    <div class="styledbutton"><a href="javascript:void()" onclick="removeItem(<?=$items['id']?>)" class="large">-</a></div>
    <div class="price">
        <?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($items['price']*$items['qty']))?><?= $this->config->item('price_suffix'); ?>
    </div>
    <div class="clear"></div>
</div>
<?php endforeach; ?>
<div class="total">
   <?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($total))?><?= $this->config->item('price_suffix'); ?>
</div>
<div class="clear"></div>
<div class="proceed">
    <div class="styledbutton"><a href="<?=base_url()?><?=$orderUrl?>"><?=$this->lang->line('placeorder');?></a></div>
</div>
<div class="clear"></div>