<?php
/**
 * 
 */

class PresentContent extends ElggObject {

	protected function initializeAttributes() {
		parent::initializeAttributes();

		$this->attributes['subtype'] = "present";
	}

	public function getURL() {
		return elgg_normalize_url('present/view/' . $this->guid);
	}
}
