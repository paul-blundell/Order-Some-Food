<div id="takeaway-header">
    <div class="name">
        <h2>Administrator Area</h2>
    </div>
    <div class="right">
        <ul>
            <li <?=($this->router->fetch_class() == 'awaiting') ? 'class="active"' : '' ?>><a href="<?=base_url('admin/awaiting')?>"><?=$this->lang->line('awaiting');?></a></li>
            <li <?=($this->router->fetch_class() == 'takeaway_list') ? 'class="active"' : '' ?>><a href="<?=base_url('admin/takeaway_list')?>"><?=$this->lang->line('takeaways');?></a></li>
        </ul>
    </div>
    <div class="clear"></div>
</div>