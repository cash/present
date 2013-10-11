<?php

class PresentPageHandler {
	public function route(array $segments = array()) {
		$page = array_shift($segments);
		if ($page) {
			$method = "serve" . ucfirst($page);
			if (method_exists($this, $method)) {
				$sections = $this->$method($segments);
				if ($sections) {
					$this->render($sections);
					return true;
				}
			}
		}

		return false;
	}

	public function render($sections) {
		$body = elgg_view_layout('content', $sections);
		echo elgg_view_page($sections['title'], $body);
	}

	protected function serveAdd($segments) {
		return $this->serveUpload($segments);
	}

	protected function serveUpload($segments) {

		$container_guid = array_shift($segments);

		$form_vars = array('enctype' => 'multipart/form-data');
		$body_vars = $this->prepareUploadFormVars();
		$content = elgg_view_form('present/upload', $form_vars, $body_vars);

		return array(
			'title' => 'hello',
			'content' => $content,
			'filter' => '',
		);
	}

	protected function serveAll($segments) {
		elgg_register_title_button();

		$content = elgg_list_entities(array(
			'type' => 'object',
			'subtype' => 'present',
			'no_results' => elgg_echo('present:none'),
		));

		return array(
			'title' => 'hello',
			'content' => $content,
			'filter_context' => 'all',
		);
	}

	protected function serveView($segments) {

		$guid = array_shift($segments);
		$object = get_entity($guid);
		if (!$object) {
			return false;
		}

		$content = elgg_view_entity($object);
		$content .= elgg_view_comments($object);

		return array(
			'title' => $object->title,
			'content' => $content,
			'filter' => '',
		);
	}

	protected function serveImage($segments) {
		$guid = array_shift($segments);
		$filename = array_shift($segments);

		$object = get_entity($guid);
		if (!$object) {
			return false;
		}

		$dirTool = new PresentDirTool($object->getOwnerGUID(), elgg_get_data_path());
		$path = $dirTool->getAbsolutePath('present/' . $guid . '/' . $filename);

		header("Content-type: image/jpeg");
		header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', strtotime("+10 days")), true);
		header("Pragma: public", true);
		header("Cache-Control: public", true);
		readfile($path);
		exit;
	}

	protected function prepareUploadFormVars() {
		// input names => defaults
		$values = array(
			'title' => '',
			'description' => '',
			'access_id' => ACCESS_DEFAULT,
			'tags' => '',
			'container_guid' => elgg_get_page_owner_guid(),
		);

		if (elgg_is_sticky_form('present')) {
			$sticky_values = elgg_get_sticky_values('present');
			foreach ($sticky_values as $key => $value) {
				$values[$key] = $value;
			}
		}

		elgg_clear_sticky_form('present');

		return $values;
	}
}
