<!DOCTYPE html>
<html>
<head>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<title><?=$takeawayname?></title>
<base href="<?= base_url(); ?>" />
<link href="css/own-styles.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/jquery.rating.css" rel="stylesheet" type="text/css" media="screen" />
<link href="css/thickbox.css" rel="stylesheet" type="text/css" media="screen" />
<script type="text/javascript" src="js/jquery.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.7.1.custom.min.js"></script>
<script type="text/javascript" src="js/thickbox-compressed.js"></script>
<?=$customCss?>
</head>
<body>
<div id="not-real">
	<strong><?=$this->lang->line('notreal');?></strong>
</div>
<div id="wrapper">
	<?php if ($ads): ?>
	<div id="top-banner-wrapper">
		<div id="top-banner">
			<div id="logo">
				<h1><a href="<?= base_url(); ?>">Order Some Food</a></h1>
				<p><?=$this->lang->line('tagline');?></p>
			</div>
			<div class="mini-search-box">
				<h3><?=$this->lang->line('minisearch');?>:</h3>
				<form method="get" action="<?= base_url(); ?>find/">
				<input id="mini-search-textbox" class="textbox" type="text" name="location" title="<?=$this->lang->line('defaultval');?>" />
				<input class="button" type="submit" name="submit" value="<?=$this->lang->line('search');?>" />
				</form>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<div id="header" class="container">
	</div>
	<div id="page" class="container">
		<div id="content-full">
			<?= $contents ?>
			<div style="clear: both;">&nbsp;</div>
		</div>
		<div style="clear: both;">&nbsp;</div>
	</div>
</div>
<div id="footer-content">
	<div id="footer-bg">	
		<div id="footer">
			<p><?=$this->lang->line('copyright');?> &copy; 2017 Paul Blundell</p>
			<p class="links"><a href="#"><?=$this->lang->line('terms');?></a><a href="#"><?=$this->lang->line('privacy');?></a><a href="<?=base_url('takeaway/register')?>"><?=$this->lang->line('own');?></a><a href="#"><?=$this->lang->line('contactus');?></a></p>
		</div>
	</div>
</div>
<script type="text/javascript">
$(document).ready(function() {
	$("#mini-search-textbox").focus(function(srcc)  {
		if ($(this).val() == $(this)[0].title) {
			    $(this).removeClass("textboxActive");
			    $(this).val("");
		}
	});
				    
	$("#mini-search-textbox").blur(function() {
		if ($(this).val() == "") {
			    $(this).addClass("textboxActive");
			    $(this).val($(this)[0].title);
		}
	});
				    
	$("#mini-search-textbox").blur();        
});
</script>
<?php if(isset($javascript)) echo $javascript; ?>
</body>
</html>
