<?php
/**
 * The wire's JavaScript
 */
?>

elgg.provide('elgg.present');

elgg.present.init = function() {
	$.deck('.slide', {
	});
};

elgg.register_hook_handler('init', 'system', elgg.present.init);

<?php

$deck_path = elgg_get_config('path') . 'mod/present/vendor/deck';

readfile("$deck_path/deck.core.js");
readfile("$deck_path/deck.navigation.js");
readfile("$deck_path/deck.status.js");
