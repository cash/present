<?php

class PresentContentFactory {
	public function create($contentType, array $params, array $files) {
		$object = new PresentContent();
		$object->owner_guid = elgg_get_logged_in_user_guid();
		$object->container_guid = $params['container_guid'];
		$object->access_id = $params['access_id'];
		$object->title = $params['title'];
		$object->description = $params['description'];
		$object->tags = $params['tags'];
		$object->content_type = $contentType;
		$guid = $object->save();

		$directory = 'present/' . $guid;
		$tool = new PresentDirTool(elgg_get_logged_in_user_guid(), elgg_get_data_path());
		$tool->mkdir($directory);

		if ($contentType == 'pdf') {
			$processor = new PresentPdfProcessor($tool->getAbsolutePath($directory), $files[0]);
			$num_pages = $processor->process();
			$object->num_pages = $num_pages;
		} else {
			$processor = new PresentImageSequenceProcessor($tool->getAbsolutePath($directory), $files);
			$num_pages = $processor->process();
			$object->num_pages = $num_pages;
		}

		return $object;
	}
}
