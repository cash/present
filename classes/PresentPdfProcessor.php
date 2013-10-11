<?php

use Slicer\Slicer;
use Slicer\Pdf;
use Slicer\Page;

/**
 * Converts a PDF into a set of images
 */
class PresentPdfProcessor {
	protected $baseDir;
	protected $pdfFilename;

	/**
	 * Create the object for processing a pdf
	 * 
	 * @param string $baseDir     The directory to store the images in. Must exist.
	 * @param string $pdfFilename The path to the pdf file
	 * @throws InvalidArgumentException
	 */
	public function __construct($baseDir, $pdfFilename) {
		if (!is_dir($baseDir)) {
			throw new InvalidArgumentException("$baseDir is not a directory or does not exist");
		}
		if (!file_exists($pdfFilename)) {
			throw new InvalidArgumentException("$pdfFilename does not exist");
		}

		$this->baseDir = rtrim($baseDir, '/');
		$this->pdfFilename = $pdfFilename;
	}

	/**
	 * Extract the pages of the pdf as images
	 * 
	 * The images are named image_0001.jpg and so on
	 * 
	 * @return int The number of pages in the pdf
	 */
	public function process() {
		$slicer = new Slicer();

		try {
			$pdf = $slicer->create($this->pdfFilename);
		} catch (RuntimeException $ex) {
			elgg_log($ex->getMessage(), 'ERROR');
			return 0;
		}

		foreach ($pdf as $index => $page) {
			$filename = sprintf('%1$s/image_%2$04d.jpg', $this->baseDir, $index + 1);
			$page->export($filename);
		}

		return count($pdf);
	}
}
