<div class="filter">
    <?=$this->lang->line('sort');?> <select>
        <option value="distance"><?=$this->lang->line('closest');?></option>
        <option value="name"><?=$this->lang->line('alpha');?></option>
        <option value="rating"><?=$this->lang->line('popular');?></option>
    </select>    
</div>
<div id="takeaway-list">
<?php foreach($xml->takeaway AS $takeaway): ?>
<div class="takeaway greyborder">
    <div class="hidden sortname"><?=$takeaway->name?></div>
    <div class="hidden sortdistance"><?=$takeaway->time?></div>
    <div class="hidden sortrating"><?=$takeaway->rating?></div>
    
    <a class="box" href="<?=base_url()?>menu/takeaway/<?=$takeaway->id?>">
    <?php if ($takeaway->status == 0): ?>
    <div class="closed"><img src="<?=base_url()?>images/closed.png" alt="closed" /></div>
    <?php endif; ?>
    <div class="logo">
    <img src="<?=$this->takeaways->getLogo($takeaway->id)?>" alt="No logo available" />
    </div>
    <div class="name">
    <h2><?= $takeaway->name ?></h2>
    <p>
    <?= $takeaway->description ?>
    </p>
    </div>
    <div class="icons">
        <?php
        $rating = round($takeaway->rating);
        for($i=1;$i<=$rating;$i++): ?>
        <img src="<?=base_url()?>images/staron.png" alt="1" />
        <?php endfor; ?>
        <?php for($i=1;$i<=(5-$rating);$i++): ?>
        <img src="<?=base_url()?>images/staroff.png" alt="1" />
        <?php endfor; ?>
    </div>
    <div class="clear"></div>
    </a>
</div>
<?php endforeach; ?>
</div>