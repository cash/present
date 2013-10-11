<?php
/**
 * 
 */

$deck_path = elgg_get_config('path') . 'mod/present/vendor/deck';

readfile("$deck_path/deck.core.css");
readfile("$deck_path/deck.navigation.css");
readfile("$deck_path/deck.status.css");
