<?php
/**
 * 
 */

$object = get_entity(get_input('guid'));
if (elgg_instanceof($object, 'object', 'present')) {

	$dir_tool = new PresentDirTool($object->getOwnerGUID(), elgg_get_data_path());
	$dir_tool->rmdir("present/" . $object->guid);

	if ($object->delete()) {
		system_message(elgg_echo('pages:delete:success'));
		forward();
	}
}

register_error(elgg_echo('pages:delete:failure'));
forward(REFERER);
