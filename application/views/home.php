<div id="search-box">
<h2><?=$this->lang->line('where');?></h2>
<form method="get" action="<?= base_url(); ?>find/">
<p class="warning">
<?php if ($invalid): ?>
<?=$this->lang->line('invalid');?>
<?php endif; ?>
</p>
<input class="textbox" type="text" name="location" title="<?=$this->lang->line('defaultval');?>" />
<input class="button" type="submit" name="submit" value="<?=$this->lang->line('search');?>" />
</form>
</div>
<div id="start-here">
<img src="<?= base_url(); ?>images/arrow.png" alt="<?=$this->lang->line('start');?>" />
</div>