<?php

class PresentRequestHandler {
	public function route(array $segments = array()) {
		$page = array_shift($segments);
		if ($page) {
			$requestMethod = strtolower($_SERVER['REQUEST_METHOD']);
			$method = "serve" . ucfirst($requestMethod) . ucfirst($page);
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

	protected function serveGetUpload_pdf($segments) {
		array_push($segments, 'pdf');
		return $this->serveGetUpload($segments);
	}

	protected function serveGetUpload_images($segments) {
		array_push($segments, 'images');
		return $this->serveGetUpload($segments);
	}

	protected function serveGetUpload($segments) {

		$container_guid = array_shift($segments);
		elgg_set_page_owner_guid($container_guid);

		$upload_type = array_shift($segments);

		$title = elgg_echo("present:title:upload:$upload_type");

		elgg_push_breadcrumb(elgg_echo('present:title'), 'present/all');
		elgg_push_breadcrumb($title);

		$form_vars = array(
			'enctype' => 'multipart/form-data',
			'action' => elgg_normalize_url('present/upload'),
		);
		$body_vars = $this->prepareUploadFormVars();
		$body_vars['upload_type'] = $upload_type;
		$content = elgg_view_form('present/upload', $form_vars, $body_vars);

		return array(
			'title' => $title,
			'content' => $content,
			'filter' => '',
		);
	}

	// todo: add CSRF protection
	protected function servePostUpload($segment) {

		if (!elgg_is_logged_in()) {
			$user = get_user(39);
			login($user);
			set_input('container_guid', $user->guid);
		}

		ajax_action_hook();
		elgg_make_sticky_form('present');

		$params = array();
		$params['title'] = get_input('title');
		$params['description'] = get_input('description');
		$params['access_id'] = get_input('access_id');
		$params['tags'] = string_to_tag_array(get_input('tags'), '');
		$params['container_guid'] = get_input('container_guid', elgg_get_logged_in_user_guid());

		$result = $this->validateUpload($params, $_FILES['file']);
		if (!$result['success']) {
			foreach ($result['errors'] as $error)  {
				register_error($error);
			}
			forward(REFERER);
		}

		if (is_array($_FILES['file']['name'])) {
			$numFiles = count($_FILES['file']['name']);
			$mimeType = $_FILES['file']['type'][0];
		} else {
			$numFiles = 1;
			$mimeType = $_FILES['file']['type'];
		}

		if (stripos($mimeType, 'image') === 0) {
			$type = 'images';
			$files = $_FILES['file']['tmp_name'];
		} else if (substr($mimeType, -3) === 'pdf') {
			$type = 'pdf';
			$files = array($_FILES['file']['tmp_name']);
		} else {
			echo 'todo - handle unknown types';
			exit;
		}

		$factory = new PresentContentFactory();
		$object = $factory->create($type, $params, $files);
		if ($object) {
			elgg_clear_sticky_form('present');
			system_message(elgg_echo('present:success:upload'));
			forward($object->getURL());
		} else {
			register_error('snap...something went wrong');
		}
	}

	protected function validateUpload(array $params, array $file) {
		$result = array('success' => true, 'errors' => array());
		if (empty($file['name'])) {
			$result['success'] = false;
			$result['errors'][] = elgg_echo('present:error:no_file');
		}

		// check each file upload for error $_FILES['file']['error'] != 0

		return $result;
	}

	protected function serveGetAll($segments) {
		elgg_register_title_button('present', 'upload_pdf');
		elgg_register_title_button('present', 'upload_images');

		elgg_push_breadcrumb(elgg_echo('present:title'));

		$content = elgg_list_entities(array(
			'type' => 'object',
			'subtype' => 'present',
			'no_results' => elgg_echo('present:none'),
		));

		return array(
			'title' => elgg_echo('present:title:all'),
			'content' => $content,
			'filter_context' => 'all',
		);
	}

	protected function serveGetOwner($segments) {
		elgg_register_title_button('present', 'upload_pdf');
		elgg_register_title_button('present', 'upload_images');

		$owner = elgg_get_page_owner_entity();

		elgg_push_breadcrumb(elgg_echo('present:title'), 'present/all');
		elgg_push_breadcrumb($owner->name);

		$content = elgg_list_entities(array(
			'type' => 'object',
			'subtype' => 'present',
			'container_guid' => $owner->guid,
			'no_results' => elgg_echo('present:none'),
		));

		return array(
			'title' => elgg_echo('present:title:owner'),
			'content' => $content,
			'filter_context' => 'mine',
		);
	}

	protected function serveGetFriends($segments) {
		elgg_register_title_button('present', 'upload_pdf');
		elgg_register_title_button('present', 'upload_images');

		$owner = elgg_get_page_owner_entity();

		elgg_push_breadcrumb(elgg_echo('present:title'), 'present/all');
		elgg_push_breadcrumb($owner->name, "pages/owner/$owner->username");
		elgg_push_breadcrumb(elgg_echo('friends'));

		$content = elgg_list_entities(array(
			'type' => 'object',
			'subtype' => 'present',
			'relationship' => 'friend',
			'relationship_guid' => $owner->guid,
			'relationship_join_on' => 'container_guid',
			'no_results' => elgg_echo('present:none'),
		));

		return array(
			'title' => elgg_echo('present:title:owner'),
			'content' => $content,
			'filter_context' => 'friends',
		);
	}

	protected function serveGetView($segments) {

		$guid = array_shift($segments);
		$object = get_entity($guid);
		if (!$object) {
			return false;
		}

		elgg_push_breadcrumb(elgg_echo('present:title'), 'present/all');

		$content = elgg_view_entity($object);
		$content .= elgg_view_comments($object);

		return array(
			'title' => $object->title,
			'content' => $content,
			'filter' => '',
		);
	}

	protected function serveGetImage($segments) {
		$guid = array_shift($segments);
		$filename = array_shift($segments);

		// validate filename here

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
