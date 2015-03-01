<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<menu>
    <takeaway>
        <name><?=$takeaway->name?></name>
        <description><?=$takeaway->description?></description>
        <openfrom>unknown</openfrom>
        <delivery><?=$takeaway->deliveryCharge?></delivery>
        <rating><?=$takeaway->rating?></rating>
        <status><?=$takeaway->status?></status>
        <id><?=$takeaway->takeawayId?></id>
    </takeaway>
    
    <?php foreach($categories as $category):?>
    
    <?php if (isset($menu[$category->categoryId])): ?>
    <category name="<?=$category->category_name?>" id="<?=$category->categoryId?>">
    
        <?php foreach($menu[$category->categoryId] AS $item):?>
    
            <item>
                <id><?=$item['id']?></id>
                <name><?=$item['name']?></name>
                <description><?=$item['description']?></description>
                <price><?=$item['price']?></price>
                
                <?php foreach($item['children'] AS $child):?>
                    <item>
                        <id><?=$child->menuId?></id>
                        <name><?=$child->name?></name>
                        <price><?=$child->price?></price>
                    </item>
                <?php endforeach; ?>
            </item>
            
        <?php endforeach; ?>
        
    </category>

    <?php endif; ?>
    <?php endforeach;?>
</menu>
