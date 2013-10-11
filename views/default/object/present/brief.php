<?php
/**
 * 
 */

$object = $vars['entity'];

$params = array(
	'entity' => $object,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
	'content' => $excerpt,
);
$params = $params + $vars;
$list_body = elgg_view('object/elements/summary', $params);

echo elgg_view_image_block($file_icon, $list_body);
