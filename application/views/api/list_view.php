<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<search>
    <?php foreach($results as $row):?>
    <takeaway>
        <name><?=$row->name?></name>
        <description><?=$row->description?></description>
        <openfrom>unknown</openfrom>
        <time><?=round($row->distance*20)?></time>
        <category><?=$row->category_name?></category>
        <rating><?=($row->rating) ? $row->rating : 0?></rating>
        <status><?=$row->status?></status>
        <id><?=$row->takeawayId?></id>
        <longitude><?=$row->longitude?></longitude>
        <latitude><?=$row->latitude?></latitude>
    </takeaway>
    <?php endforeach;?>
</search>