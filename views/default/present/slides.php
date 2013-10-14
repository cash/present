<?php
/**
 * Create the html for a set of slides
 * 
 * @uses $vars['entity'] PresentContent object
 */

$object = $vars['entity'];

$num_slides = $object->num_pages;
$base_url = elgg_get_site_url() . 'present/image/' . $object->guid;

echo '<article class="deck-container">';
for ($index = 1; $index <= $num_slides; $index++) {
	$id = sprintf('%1$04d', $index);
	echo "<section class=\"slide\"><img src=\"$base_url/image_$id.jpg\" /></section>";
}

echo elgg_view('present/controls', $vars);

echo '</article>';
