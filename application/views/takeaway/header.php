<div class="takeaway">
    
    <?php if ($takeaway->status == 0): ?>
    <div class="closed"><img src="<?=base_url()?>images/closed.png" alt="Closed" /></div>
    <?php endif; ?>
    
    <div class="logo">
        <img src="<?=$this->takeaways->getLogo($takeaway->takeawayId)?>" alt="No logo available" />
    </div>
    
    <div class="name">
    <h2><a href="<?=$menuUrl?>"><?=$takeaway->name ?></a></h2>
    <p><?=$takeaway->description ?></p>
    </div>
    
    <div class="clear"></div>
</div>