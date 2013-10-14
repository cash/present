<?php
/**
 * 
 */

elgg_load_js('present');
elgg_load_css('present');
elgg_load_css('font-awesome');

$object = $vars['entity'];

$metadata = elgg_view_menu('entity', array(
	'entity' => $object,
	'handler' => 'present',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$params = array(
	'entity' => $object,
	'title' => false,
	'metadata' => $metadata,
	'subtitle' => $subtitle,
);
$params = $params + $vars;
$summary = elgg_view('object/elements/summary', $params);

$text = elgg_view('output/longtext', array('value' => $object->description));

$body = elgg_view('present/slides', $vars);
$body .= $text;

$file_icon = elgg_view_entity_icon($object, 'small', array('href' => false));

echo elgg_view('object/elements/full', array(
	'entity' => $object,
	'icon' => $file_icon,
	'summary' => $summary,
	'body' => $body,
));
