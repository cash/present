<?php
/**
 * 
 */

use Slicer\Slicer;
use Slicer\Pdf;
use Slicer\Page;

class PresentPdfHandler {
	protected $baseDir;
	protected $pdfFilename;

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

	public function extract() {
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
