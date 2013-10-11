<?php

/**
 * Stores a sequence of images
 */
class PresentImageSequenceProcessor {
	protected $baseDir;
	protected $imageFilenames;

	/**
	 * Create the object for processing the images
	 * 
	 * @param string $baseDir        The directory to store the images in. Must exist.
	 * @param array  $imageFilenames Array of image filenames (relative or absolute paths)
	 * @throws InvalidArgumentException
	 */
	public function __construct($baseDir, array $imageFilenames) {
		if (!is_dir($baseDir)) {
			throw new InvalidArgumentException("$baseDir is not a directory or does not exist");
		}
		if (!$imageFilenames) {
			throw new InvalidArgumentException("No images for processing");			
		}
		foreach ($imageFilenames as $filename) {
			if (!file_exists($filename)) {
				throw new InvalidArgumentException("$filename does not exist");
			}
		}

		$this->baseDir = rtrim($baseDir, '/');
		$this->imageFilenames = $imageFilenames;
	}

	/**
	 * Process the images
	 * 
	 * @return int The number of images
	 */
	public function process() {

		foreach ($this->imageFilenames as $index => $imagePath) {
			$filename = sprintf('%1$s/image_%2$04d.jpg', $this->baseDir, $index + 1);
			copy($imagePath, $filename);
		}

		return count($this->imageFilenames);
	}
}
