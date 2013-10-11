<?php
/**
 * Render a present content object
 * 
 * @uses $vars['entity']    PresentContent object
 * @uses $vars['full_view'] 
 */

$full_view = elgg_extract('full_view', $vars, true);

if ($full_view) {
	echo elgg_view('object/present/full', $vars);
} else {
	echo elgg_view('object/present/brief', $vars);
}
