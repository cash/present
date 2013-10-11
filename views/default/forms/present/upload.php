<?php
/**
 * 
 */

// once elgg_view stops throwing all sorts of junk into $vars, we can use extract()
$title = elgg_extract('title', $vars, '');
$desc = elgg_extract('description', $vars, '');
$tags = elgg_extract('tags', $vars, '');
$access_id = elgg_extract('access_id', $vars, ACCESS_DEFAULT);
$container_guid = elgg_extract('container_guid', $vars);

?>
<div>
	<label><?php echo elgg_echo('title'); ?></label><br />
	<?php echo elgg_view('input/text', array('name' => 'title', 'value' => $title)); ?>
</div>
<div>
	<label><?php echo elgg_echo('file'); ?></label><br />
	<?php echo elgg_view('input/file', array('name' => 'file')); ?>
</div>
<div>
	<label><?php echo elgg_echo('description'); ?></label>
	<?php echo elgg_view('input/longtext', array('name' => 'description', 'value' => $desc)); ?>
</div>
<div>
	<label><?php echo elgg_echo('tags'); ?></label>
	<?php echo elgg_view('input/tags', array('name' => 'tags', 'value' => $tags)); ?>
</div>
<div>
	<label><?php echo elgg_echo('access'); ?></label><br />
	<?php echo elgg_view('input/access', array('name' => 'access_id', 'value' => $access_id)); ?>
</div>
<div class="elgg-foot">
<?php

echo elgg_view('input/hidden', array('name' => 'container_guid', 'value' => $container_guid));

echo elgg_view('input/submit', array('value' => elgg_echo("upload")));

?>
</div>
