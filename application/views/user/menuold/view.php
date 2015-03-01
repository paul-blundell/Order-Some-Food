<?php $this->load->view('user/header'); ?>
<p></p>
<a href="<?=base_url('takeaway/menu/category/')?>?height=400&width=370" class="thickbox"><img src="<?=base_url()?>images/addcategory.png" alt="Add Category" /></a>
<p></p> 
<?php foreach($xml->category AS $category): ?>
        <?php $attributes = $category->attributes(); ?>
        <div class="category">
            <div class="head">
                <div class="left">
                <h2><?=$attributes[0];?></h2>
                </div>
                <div class="right">
                    <a href="<?=base_url('takeaway/menu/add/'.$attributes[1])?>?height=400&width=370" class="thickbox"><img src="<?=base_url()?>images/addproduct.png" alt="Add Product" /></a>
                </div>
                <div class="clear"></div>
            </div>
            <div class="items">
                <?php foreach($category->item AS $item): ?>
                <div class="item">
                    <div class="left">
                        <a href="javascript:void()" onclick="removeItem(<?=$item->id?>)"><img class="icon" src="<?=base_url()?>images/remove.png" alt="add" title="Remove Product" /></a>
                        <a href="<?=base_url('takeaway/menu/edit/'.$item->id)?>?height=400&width=370" class="thickbox"><img class="icon" src="<?=base_url()?>images/edit.png" alt="edit" title="Edit Product" /></a>
                        <?=$item->name?>
                    </div>
                    <?php if (isset($item->item)): ?>
                        <?php foreach($item->item AS $child): ?>
                            <div class="details">
                            <?=$child->name; ?> - <?= $this->config->item('price_prefix'); ?><?=money_format('%i',floatval($child->price)); ?><?= $this->config->item('price_suffix'); ?>
                            </div><br/>
                        <?php endforeach; ?>
                    <?php else: ?>
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