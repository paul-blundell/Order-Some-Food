<div id="takeaway-edit">
<form method="post" action="">
<h2>Add Menu Item</h2>
<label>Name</label><input type="text" name="name"/><br/>
<div class="nochild<?=($children)?' hidden':'' ?>">
<label>Price</label><input type="text" name="price" size="4"/>
</div>
<div class="child<?=(!$children)?' hidden':'' ?>">
    <table class="children">
        <tr><th>Name</th><th>Price</th><th></th></tr>
    </table>
    <div class="right">
    <a href="javascript:void()" onclick="addRow()">Add Another Row</a>
    </div>
</div>
<div class="clear"></div>
<input type="hidden" name="category" value="<?=$category?>"/>
<input id="productadd" type="hidden" name="productadd"/>
<div class="update">
<input type="submit" name="submit" value="Update" />
</div>
<div class="nochild<?=($children)?' hidden':'' ?>">
<a href="javascript:void()" onclick="addChild()">Add Child Products</a> (Such as different sizes or variants)
</div>
</form>
<script type="text/javascript">
    function addRow() {
        var id = $('.children tr').length + 1;
        $('.children tr:last').after('<tr id="row'+id+'"><input type="hidden" name="child[]" value="0" />\
                                        <td><input type="text" name="childname[]"/></td>\
                                        <td><input type="text" name="childprice[]" /></td>\
                                        <td><a href="javascript:void()" onclick="remove(\'row'+id+'\')"><img src="<?=base_url()?>images/remove.png" alt="Remove" /></a></td>\
                                    </tr>');
    }
    
    function remove(id) {
        $('#'+id).remove();
        
        var count = $('.children tr').length;
            
        // Only 1 tr row - which is heading so revert back to no children
        if (count == 1)
            hideChild();
                
    }
    function addChild() {
        $('.nochild').hide();
        $('.child').show();
        addRow();
    }
    function hideChild() {
        $('.nochild').show();
        $('.child').hide();
    }

</script>