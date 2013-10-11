<?php
/**
 * 
 */

elgg_make_sticky_form('present');

$title = get_input('title');
$description = get_input('description');
$access_id = get_input('access_id');
$tags = string_to_tag_array(get_input('tags'), '');
$container_guid = get_input('container_guid', elgg_get_logged_in_user_guid());


if (empty($_FILES['file']['name'])) {
	register_error(elgg_echo('present:error:no_file'));
	forward(REFERER);
}

// check if upload failed
if (!empty($_FILES['file']['name']) && $_FILES['file']['error'] != 0) {
	register_error(elgg_echo('file:cannotload'));
	forward(REFERER);
}

$filename = $_FILES['file']['tmp_name'];

$object = new PresentContent();
$object->owner_guid = elgg_get_logged_in_user_guid();
$object->container_guid = $container_guid;
$object->access_id = $access_id;
$object->title = $title;
$object->description = $description;
$object->tags = $tags;
$guid = $object->save();

if ($guid) {
	elgg_clear_sticky_form('present');

	$directory = 'present/' . $guid;
	$tool = new PresentDirTool(elgg_get_logged_in_user_guid(), elgg_get_data_path());
	$tool->mkdir($directory);
	$extractor = new PresentPdfHandler($tool->getAbsolutePath($directory), $filename);
	$num_pages = $extractor->extract();
	$object->num_pages = $num_pages;
}

forward();
