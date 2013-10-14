<?php
/**
 * Controls for slides/presentation
 * 
 */

$object = $vars['entity'];

if ($object->content_type === 'pdf') {
	$controls = '<div class="present-control present-control-backward deck-prev-link"></div>';
	$controls .= '<div class="present-control present-control-forward deck-next-link"></div>';
	$controls .= '<div class="present-control present-control-status deck-status">
		<span class="deck-status-current"></span>/<span class="deck-status-total"></span></div>';
} else {
	$controls = '<div class="present-control present-control-play deck-automatic-link"></div>';	
}

echo <<<HTML
<div class="present-control-bar">
	$controls
</div>
HTML;
