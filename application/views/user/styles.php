<?php $this->load->view('user/header'); ?>
<p><?=$this->lang->line('direct')?> <strong><a href="<?=base_url($takeaway->shortname)?>"><?=base_url($takeaway->shortname)?></a></strong><br/><?=$this->lang->line('directexpalin')?><br/></p>
<p><?=$this->lang->line('explain')?></p>
<p><br/></p>
<form method="post" action="">
<label class="leftlabel"><?=$this->lang->line('background');?></label><input type="text" name="background" value="<?=$takeaway->background?>"/> <?=$this->lang->line('backgroundDesc')?><br/>
<label class="leftlabel"><?=$this->lang->line('fontsize');?></label><input type="text" name="fontsize" size="2" value="<?=$takeaway->fontsize?>" />px<br/>
<label class="leftlabel"><?=$this->lang->line('button');?></label><input type="text" name="button" value="<?=$takeaway->buttons?>" /><br/>
<label class="leftlabel"><?=$this->lang->line('category');?></label><input type="text" name="category" value="<?=$takeaway->categoryColour?>" />
<div class="clear"></div>
<input type="submit" name="submit" value="<?=$this->lang->line('update');?>" />
</form>
