<p><?=$this->lang->line('paymentoption');?></p>
<?php 
$text = $this->lang->line('cashcollection');
if($_POST['delivery'] == 2)
	$text = $this->lang->line('cashdelivery');
?>
<form method="post" action="<?=base_url('order/placeorder')?>" id="detailsForm">
<input type="hidden" name="paymentReview" value="true" />
<input class="button" type="submit" value="<?=$text?>" />
<?=$button?>
</form>

