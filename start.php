<?php

require __DIR__ . '/vendor/autoload.php';

elgg_register_event_handler('init', 'system', 'present_init');

function present_init() {
	elgg_register_page_handler('present', 'present_page_handler');

	$item = new ElggMenuItem('present', elgg_echo('present:title'), 'present/all');
	elgg_register_menu_item('site', $item);

	elgg_register_entity_type('object', 'present');

	$present_js = elgg_get_simplecache_url('js', 'present');
	elgg_register_simplecache_view('js/present');
	elgg_register_js('present', $present_js, 'footer');
	$present_css = elgg_get_simplecache_url('css', 'present');
	elgg_register_simplecache_view('css/present');
	elgg_register_css('present', $present_css);
	
	elgg_register_css('font-awesome', 'mod/present/vendor/fortawesome/font-awesome/css/font-awesome.min.css');

	$actions_base = elgg_get_plugins_path() . 'present/actions/present';
	elgg_register_action('present/delete', "$actions_base/delete.php");
}

function present_page_handler($segments) {
	$handler = new PresentRequestHandler();
	return $handler->route($segments);
}
