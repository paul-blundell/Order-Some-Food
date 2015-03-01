<div id="takeaway-edit">
<form method="post" action="">
<label>Name</label><input type="text" name="name" value="<?=$item->name?>"/><br/>
<label>Description</label><textarea id="desc" name="desc" cols="19" rows="3"><?=$item->description?></textarea><div id="desc-count" class="right">Remaining Chars:<br/><span>100</span></div><br/>
<div class="nochild<?=($children)?' hidden':'' ?>">
<label>Price</label><input type="text" name="price" size="4" value="<?=$item->price?>"/>
</div>
<div class="child<?=(!$children)?' hidden':'' ?>">
    <table class="children">
        <tr><th>Name</th><th>Price</th><th></th></tr>
        <?php
        $count = 1;
        foreach ($children AS $child): ?>
            <tr id="row<?=$count?>">
                <input type="hidden" name="child[]" value="<?=$child->menuId?>" />
                <td><input type="text" name="childname[]" value="<?=$child->name?>" /></td>
                <td><input type="text" name="childprice[]" size="7" value="<?=$child->price?>" /></td>
                <td><a href="javascript:void()" onclick="remove('row<?=$count?>')"><img src="<?=base_url()?>images/remove.png" alt="Remove" /></a></td>
            </tr>
            <?php $count++; ?>
        <?php endforeach; ?>
    </table>
    <div class="right">
    <a href="javascript:void()" onclick="addRow()">Add Another Row</a>
    </div>
</div>
<div class="clear"></div>
<input type="hidden" name="category" value="<?=$item->categoryId?>"/>
<input id="productupdate" type="hidden" name="productupdate" value="<?=$product?>"/>
<div class="update">
<input id="submit" type="submit" name="submit" value="Update" />
</div>
<div class="nochild<?=($children)?' hidden':'' ?>">
<a href="javascript:void()" onclick="addChild()">Add Child Products</a> (Such as different sizes or variants)
</div>
</form>
<script type="text/javascript">
    $(document).ready(function() {
        $("#desc").keyup(function(){
            var count = 100 - $(this).val().length;
            if(count < 0){
                $("#desc-count span").css("color", "red");
                $('#submit').attr("disabled", true);
            } else {
                $("#desc-count span").css("color", "black");
                $('#submit').attr("disabled", false);
            }
            
            $("#desc-count span").text(count);
        });
    });
    
    function addRow() {
        var id = $('.children tr').length + 1;
        $('.children tr:last').after('<tr id="row'+id+'"><input type="hidden" name="child[]" value="0" />\
                                        <td><input type="text" name="childname[]"/></td>\
                                        <td><input type="text" name="childprice[]"  size="7"/></td>\
                                        <td><a href="javascript:void()" onclick="remove(\'row'+id+'\')"><img src="<?=base_url()?>images/remove.png" alt="Remove" /></a></td>\
                                    </tr>');
    }
    
    function remove(id) {
        var itemId = $('#'+id).children(':input[name=child[]]').val();
        
        // If added now then we can remove without worry
        if (itemId == 0) {
            $('#'+id).remove();
        }
        // Otherwise we need to remove from DB so add a hidden input field to tell
        // PHP to remove the field when form has posted and remove for now with jscript
        else {
            $('#productupdate').after('<input type="hidden" name="remove[]" value="'+itemId+'"/>');
            $('#'+id).remove();
        }
        
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