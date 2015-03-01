<?php $this->load->view('admin/header'); ?>
<?php if ($takeaways): ?>
    <form method="POST" action="">
    <table id="previous-orders">
    <tr><th><?=$this->lang->line('takeaway');?></th><th><?=$this->lang->line('address');?></th><th><?=$this->lang->line('phone');?></th><th><?=$this->lang->line('status');?></th></tr>
    
    <?php $options = array(0 => $this->lang->line('pending'), 1 => $this->lang->line('approved'), 2 => $this->lang->line('approvednoads')); ?>
        
    <?php foreach($takeaways AS $takeaway): ?>
    <tr>
        <td><?=$takeaway->name?></td><td><?=$takeaway->address?><br/><?=$takeaway->postcode?></td><td><?=$takeaway->phone?></td>
        <td><select name="status[<?=$takeaway->takeawayId?>]">
        <?php foreach($options AS $key=>$val): ?>
        <option value="<?=$key?>" <?=($takeaway->type == $key) ? 'selected="selected"' : ''?>><?=$val?></option>
        <?php endforeach; ?>
	<td><a href="<?= base_url('admin/takeaway_list/user/'.$takeaway->takeawayId); ?>">Switch</a></th>
        </select></td>
    </tr>
    <?php endforeach; ?>
    </table>
    <input type="submit" name="submit" value="<?=$this->lang->line('update');?>" />
    </form>
<?php else: ?>
<p><?=$this->lang->line('notakeaways');?></p>
<?php endif; ?>
