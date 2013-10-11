<?php
/**
 * 
 */

if (get_subtype_id('object', 'present')) {
	update_subtype('object', 'present', 'PresentContent');
} else {
	add_subtype('object', 'present', 'PresentContent');
}
