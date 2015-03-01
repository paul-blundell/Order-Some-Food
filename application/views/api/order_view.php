<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<orderresponse>
        <status><?=$status?></status>
        <orderId><?=(isset($orderId))?$orderId:0?></orderId>
        <message><?=(isset($message))?$message:''?></message>
</orderresponse>