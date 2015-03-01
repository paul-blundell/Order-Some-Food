<?php $this->load->view('admin/header'); ?>
<?php if ($takeaways): ?>
    <form method="POST" action="">
    <table id="previous-orders">
    <tr><th><?=$this->lang->line('takeaway');?></th><th><?=$this->lang->line('address');?></th><th><?=$this->lang->line('phone');?></th><th><?=$this->lang->line('status');?></th></tr>
        
    <?php foreach($takeaways AS $takeaway): ?>
    <tr>
        <td><?=$takeaway->name?></td><td><?=$takeaway->address?><br/><?=$takeaway->postcode?></td><td><?=$takeaway->phone?></td>
        <td><select name="status[<?=$takeaway->takeawayId?>]"><option value="0"><?=$this->lang->line('pending');?></option><option value="1"><?=$this->lang->line('approved');?></option><option value="2"><?=$this->lang->line('approvednoads');?></option><option value="3"><?=$this->lang->line('rejected');?></option></select></td>
    </tr>
    <?php endforeach; ?>
    </table>
    <input type="submit" name="submit" value="<?=$this->lang->line('update');?>" />
    </form>
<?php else: ?>
<p><?=$this->lang->line('noawaiting');?></p>
<?php endif; ?>