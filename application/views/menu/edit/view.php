<?php $this->load->view('user/header'); ?>
<p></p>
<a href="<?=base_url('takeaway/menu/category/')?>?height=100&width=370" class="thickbox" title="<?=$this->lang->line('add-category');?>"><img src="<?=base_url()?>images/addcategory.png" alt="<?=$this->lang->line('add-category');?>" /></a>
<p></p>
<div id="cats">
<?php foreach($xml->category AS $category): ?>
        <?php $attributes = $category->attributes(); ?>
        <div id="cat_<?=$attributes[1]?>" class="category">
            <div class="head">
                <div class="left caticons">
                    <img src="<?=base_url()?>images/sort.png" alt="move" width="16" height="16" class="handle" />
                    <a href="javascript:void()" onclick="removeCat(<?=$attributes[1]?>)"><img class="icon" src="<?=base_url()?>images/remove.png" alt="add" title="<?=$this->lang->line('remove-category');?>" /></a>
                    <a href="<?=base_url('takeaway/menu/editcat/'.$attributes[1])?>?height=100&width=370" class="thickbox" title="<?=$this->lang->line('edit-category');?>"><img class="icon" src="<?=base_url()?>images/edit.png" alt="edit" title="<?=$this->lang->line('edit-category');?>" /></a>
                </div>
                <div class="left">
                <h2><?=$attributes[0];?></h2>
                </div>
                <div class="right">
                    <a href="<?=base_url('takeaway/menu/add/'.$attributes[1])?>?height=450&width=370" class="thickbox" title="<?=$this->lang->line('add-item');?>"><img src="<?=base_url()?>images/addproduct.png" alt="<?=$this->lang->line('add-product');?>" /></a>
                </div>
                <div class="clear"></div>
            </div>
            <div class="items">
                <?php foreach($category->item AS $item): ?>
                <div class="item">
                    <div class="left">
                        <a href="javascript:void()" onclick="removeItem(<?=$item->id?>)"><img class="icon" src="<?=base_url()?>images/remove.png" alt="add" title="<?=$this->lang->line('remove-product');?>" /></a>
                        <a href="<?=base_url('takeaway/menu/edit/'.$item->id)?>?height=450&width=370" class="thickbox" title="<?=$this->lang->line('edit-item');?>"><img class="icon" src="<?=base_url()?>images/edit.png" alt="edit" title="<?=$this->lang->line('edit-product');?>" /></a>
                        <?=$item->name?><br/><div class="description"><?=$item->description?></div>
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
</div>