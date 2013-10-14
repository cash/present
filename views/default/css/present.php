<?php
/**
 * 
 */

$deck_path = elgg_get_config('path') . 'mod/present/vendor/deck';

readfile("$deck_path/deck.core.css");

?>

.present-control-bar {
	height: 3em;
	width: 100%;
	background-color: #eee;
	color: #aaa;
}

.present-control:before {
	font-family: FontAwesome;
	font-size: 1.5em;
	height: 100%;
	width: 100%;
	line-height: 2;
	text-align: center;
}

.present-control {
	width: 5em;
	cursor: pointer;
	text-align: center;
	float: left;
}

.present-control:hover {
	color: #999;
	text-shadow: 0 0 1em #333;
}

.present-control-play:before {
	content: "";
}

.deck-automatic-running.present-control-play:before {
	content: "";
}

.present-control-play {

}

.present-control-backward:before {
	content: "";
}

.present-control-forward:before {
	content: "";
}
