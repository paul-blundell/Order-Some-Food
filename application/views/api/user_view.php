<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<xml>
    <status><?=$status?></status>
    <?php if ($status): ?>
        <uid><?=(isset($uid)) ? $uid : '' ?></uid>
        <name><?=(isset($name)) ? $name : ''?></name>
        <gid><?=(isset($group)) ? $group : ''?><gid>
    <?php endif; ?>
</xml>