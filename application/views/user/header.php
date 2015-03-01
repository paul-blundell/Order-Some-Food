<div id="takeaway-header">
    <div class="name">
        <h2><?=$takeaway->name?></h2>
    </div>
    <div class="right">
        <ul>
            <li <?=($this->router->fetch_class() == 'orders') ? 'class="active"' : '' ?>><a href="<?=base_url('takeaway/orders')?>"><?=$this->lang->line('orders');?></a></li>
            <li <?=($this->router->fetch_class() == 'menu') ? 'class="active"' : '' ?>><a href="<?=base_url('takeaway/menu')?>"><?=$this->lang->line('menu');?></a></li>
            <li <?=($this->router->fetch_class() == 'styles') ? 'class="active"' : '' ?>><a href="<?=base_url('takeaway/styles')?>"><?=$this->lang->line('appearance');?></a></li>
            <li <?=($this->router->fetch_class() == 'details') ? 'class="active"' : '' ?>><a href="<?=base_url('takeaway/details')?>"><?=$this->lang->line('details');?></a></li>
        </ul>
    </div>
    <div class="clear"></div>
</div>