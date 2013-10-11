<?php

class PresentDirTool {
	protected $guid;
	protected $dataDir;
	protected $locator;

	public function __construct($user_guid, $data_dir) {
		$this->guid = $user_guid;
		$this->dataDir = $data_dir;
		$this->locator = new Elgg_EntityDirLocator($this->guid);
	}

	public function mkdir($dir) {
		return mkdir($this->getAbsolutePath($dir), 0755, true);
	}

	public function rmdir($dir) {
		return delete_directory($this->getAbsolutePath($dir));
	}

	public function getAbsolutePath($dir) {
		return $this->dataDir . $this->locator->getPath(). $dir;
	}
}
