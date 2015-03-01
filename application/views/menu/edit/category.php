<div id="takeaway-edit">
<form method="post" action="">
<label>Name</label><input type="text" name="name" <?=(isset($cat))?'value="'.$cat->category_name.'"':''?>/><br/>
<?php if (isset($cat)): ?>
<input type="hidden" name="cat" value="<?=$cat->categoryId?>" />
<?php endif; ?>
<div class="update">
<input type="submit" name="<?=(isset($cat))?'categoryrename':'categoryadd'?>" value="<?=(isset($cat))?'Rename':'Add'?>" />
</div>