<div class="takeaway">
<?php if ($xml->takeaway->status == 0): ?>
<div class="closed"><img src="<?=base_url()?>images/closed.png" alt="Closed" /></div>
<?php endif; ?>
<div class="logo">
    <img src="<?=$this->takeaways->getLogo($xml->takeaway->id)?>" alt="No logo available" />
</div>
<div class="name">
<h2><?=$xml->takeaway->name ?></h2>
<p><?=$xml->takeaway->description ?></p>
</div>
<div class="rating">
    <p class="ratetext"><?=$this->lang->line('avgrating');?></p>
<?php for($i=1;$i<=5;$i++): ?>
    <?php
    $checked = '';
    if ($i == round($xml->takeaway->rating))
        $checked = 'checked="checked"';
    ?>
    <input name="star2" type="radio" class="rating-star" value="<?=$i?>" <?=$checked?>/>
<?php endfor; ?>
</div>
<div class="clear"></div>
</div>
<div class="menu-left">
    <div class="column-head"><h2>Categories</h2></div>
    <div class="column-content menu-category-list">
    <?php foreach ($xml->category AS $category): ?>
    <?php $attributes = $category->attributes(); ?>
    <a href="javascript:void()" name="<?=str_replace(' ','-',$attributes[0]); ?>" class="jumpto"><?=$attributes[0];?></a>
    <?php endforeach; ?>
    </div>
</div>
<div class="menu-center">
<?php foreach($xml->category AS $category): ?>
        <?php $attributes = $category->attributes(); ?>
        <div id="<?=$attributes[0];?>" class="category">
            <div class="head">
                <h2><?=$attributes[0];?></h2>
            </div>
            <div class="items">
                <?php foreach($category->item AS $item): ?>
                <div class="item">
                    <div class="name"><?=$item->name?><br/><div class="description"><?=$item->description?></div></div>
                    <?php if (isset($item->item)): ?>
                        <?php foreach($item->item AS $child): ?>
                            <?php if ($xml->takeaway->status == 1): ?>
                                <div class="styledbutton"><a href="javascript:void()" class="small" onclick="addItem(<?=$child->id?>)">+</a></div>
                            <?php endif; ?>
                            <div class="details">
                            <?=$child->name; ?> - <?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($child->price)); ?><?= $this->config->item('price_suffix'); ?>
                            </div><br/>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <?php if ($xml->takeaway->status == 1): ?>
                        <div class="styledbutton"><a href="javascript:void()" class="small"  onclick="addItem(<?=$item->id?>)">+</a></div>
                        <?php endif; ?>
                        <div class="details">
                        <?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($item->price))?><?= $this->config->item('price_suffix'); ?>
                        </div>
                    <?php endif; ?>
                    <div class="clear"></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
<?php endforeach; ?>
</div>
<div class="menu-right">
    <div class="column-head"><h2><?=$this->lang->line('yourorder');?></h2></div>
    <div class="column-content"><div id="order"></div></div>
</div>