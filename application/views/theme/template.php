<!DOCTYPE html>
<html>
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<title>Order Some Food</title>
<base href="<?= base_url(); ?>" />
<link href="css/style.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/jquery.rating.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/thickbox.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/jquery.dataTables.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
<script type="text/javascript" src="js/thickbox-compressed.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.js"></script>
</head>
<body>
<!--<div id="not-real">
	<strong><?=$this->lang->line('notreal');?></strong>
</div>-->

<?php if ($this->user->isAdminSwitched()): ?>
<div id="notice">
	<strong><a href="<?= base_url('admin/takeaway_list/reverse'); ?>"><?=$this->lang->line('returnadmin');?></a></strong>
</div>
<?php endif; ?>

<div id="wrapper">
	<div id="header" class="container">
		<div id="logo">
			<h1><a href="<?= base_url(); ?>">Order Some Food</a></h1>
			<p><?=$this->lang->line('tagline');?></p>
		</div>
		<div id="menu">
			<div id="languages">
			<?=$this->lang->line('language');?>:
			<a href="<?=base_url('en'.uri_string())?>" title="English"><img src="<?=base_url()?>images/flags/gb.png" alt="English" /></a>
			<a href="<?=base_url('cy'.uri_string())?>" title="Welsh"><img src="<?=base_url()?>images/flags/wales.png" alt="Welsh" /></a>
			</div>
			<br/><?=$this->load->view("user/login_box");?>
		</div>
	</div>
	<div id="page" class="container">
		<div id="content-full">
			<?= $contents ?>
			<div style="clear: both;">&nbsp;</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
</div>
<div id="footer-content" class="container">
	<div id="footer-bg">
		<div id="column1">
			<h2><?=$this->lang->line('what');?></h2>
			<p><?=$this->lang->line('what-we-do');?></p>
		</div>
		<div id="column2">
			<ul>
				<li><a href="#"><?=$this->lang->line('terms');?></a></li>
				<li><a href="#"><?=$this->lang->line('privacy');?></a></li>
				<li><a href="<?=base_url('takeaway/register')?>"><?=$this->lang->line('own');?></a></li>
				<li><a href="#"><?=$this->lang->line('contactus');?></a></li>
			</ul>
		</div>
	</div>
</div>
<div id="footer">
	<p><?=$this->lang->line('copyright');?> &copy; 2017 Paul Blundell</p>
</div>
<!-- end #footer -->
<script type="text/javascript">
$(document).ready(function() {
	$(".textbox").focus(function(srcc)  {
		if ($(this).val() == $(this)[0].title) {
		    $(this).removeClass("textboxActive");
		    $(this).val("");
		}
	});
    
	$(".textbox").blur(function() {
		if ($(this).val() == "") {
		    $(this).addClass("textboxActive");
		    $(this).val($(this)[0].title);
		}
	});
    
	$(".textbox").blur();        
});
</script>
<?php if(isset($javascript)) echo $javascript; ?>
</body>
</html>
