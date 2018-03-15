<?php
namespace Core;

/**
 * Requirement for building this class
 *
 * 1. Resize multiple images in the range of sizes
 * 2. Different sizes and MIME type
 * 3. Option to set quality and resmapling method
 * 4. Prevent image from being scaled up
 * 5. Embed image watermark
 *
 *  Required
 * -------------------------------------------------
 * sonstructor, setOutputSizes(), outputImages()
 *
 *  Optioinal
 * -------------------------------------------------
 * setJpegQuality(), setPngCompression(), watermark()
 *
 */

/**
 * Image class for manipulating images
 *
 */
class Image
{
	protected $images = [];
	protected $source;
	protected $destination;
	protected $useImageScale = true;
	protected $mimeTypes = ["image/jpeg", "image/png", "image/gif"];
	protected $invalid = [];
	protected $outputSizes = [];
	protected $jpegQuality = 75;
	protected $pngCompression = 0;
	protected $resample = IMG_BILINEAR_FIXED;
	protected $watermark;
	protected $wmarkW;
	protected $wmarkH;
	protected $wmarkType;
	protected $marginR;
	protected $marginB;
	protected $generated = [];

	public function __construct(array $images, $sourceDir = null)
	{
		if(!is_null($sourceDir) && !is_dir($sourceDir)) {
			throw new \Exception("$sourceDir is not a directory.");
		}

		$this->images = $images;
		$this->source = $sourceDir;

		// Checking whether imagescale() is supported or not by currently running php
		if(PHP_VERSION_ID < 50519 || (PHP_VERSION_ID >= 50600 && PHP_VERSION_ID < 50603)) {
			$this->useImageScale = false;
		}

		$this->checkImages();
	}

	public function setOutputSizes(array $sizes)
	{
		foreach ($sizes as $size) {
			if(!is_numeric($size) || $size <= 0 ) {
				throw new \Exception("Sizes must be an array of positive numbers.");
			}
			$this->outputSizes[] = (int) $size;
		}
		if(!$this->useImageScale) {
			$this->calculateRatios();
		}
	}

	public function setJpegQuality($quality)
	{
		if(!is_numeric($quality) || ($quality <= 0 && $quality > 100 )) {
			throw new \Exception("The quality number must be number and between 0 to 100.");
		}
		$this->jpegQuality = $quality;
	}

	public function setPngCompression($number)
	{
		if(!is_numeric($number) || ($number <= 0 && $quality > 9 )) {
			throw new \Exception("The compression number must be number and between 0 to 9.");
		}
		$this->jpegQuality = $number;
	}

	public function watermark($file, $margin_right = 30, $margin_bottom = 30)
	{
		if(!file_exists($file) || !is_readable($file)) {
			throw new \Exception("Either file does not exist or is not readable.");
		}

		$size = getimagesize($file);
		if($size === false && mime_content_type($file) == "image/webp") {
			$size["mime"] = "image/webp";
		}

		if(!in_array($size["mime"], $this->mimeTypes)) {
			throw new \Exception("Webp file is not supported in the current version of my framework. Use different type of image.");
		}

		$this->watermark = $this->createImageResource($file, $size["mime"]);
		$this->wmarkW = $size[0];
		$this->wmarkH = $size[1];

		// Assigning margin of watermark relative to main image in class property
		if(is_numeric($margin_right) && $margin_right > 0) {
			$this->marginR = $margin_right;
		}
		if(is_numeric($margin_bottom) && $margin_bottom > 0) {
			$this->marginB = $margin_bottom;
		}
	}

	public function outputImages($destination)
	{
		if(!is_dir($destination) || !is_writable($destination)) {
			throw new \Exception("Either destination is not a directory or not writable.");
		}

		$this->destination = $destination;

		// Loop through source image
		foreach ($this->images as $i => $image) {
			// Skipping files that are invalid
			if(in_array($this->images[$i]["file"], $this->invalid)) {
				continue;
			}
			// Create the image resource for current image
			$resource = $this->createImageResource($this->images[$i]["file"], $this->images[$i]["mime"]);

			// Adding watermark if watermark property contain value
			if($this->watermark) {
				//print_r($this->images[$i]);
				$this->addWatermark($this->images[$i], $resource);
			}

			// Generating the output image
			$this->generateOutput($this->images[$i], $resource);
			imagedestroy($resource);
		}

		return ["output" => $this->generated, "invalid" => $this->invalid];
	}

	protected function checkImages()
	{
		foreach ($this->images as $i => $image) {
			$this->images[$i] = [];

			if($this->source) {
				$this->images[$i]["file"] = $this->source . DS . $image;
			} else {
				$this->images[$i]["file"] = $image;
			}

			if(file_exists($this->images[$i]["file"]) && is_readable($this->images[$i]["file"])) {
				$size = getimagesize($this->images[$i]["file"]);
				// Webp image is not supported by this code and not handled in it.

				if($size[0] === 0 || !in_array($size["mime"], $this->mimeTypes)) {
					$this->invalid[] = $this->images[$i]["file"];
				} else {
					if($size["mime"] == "image/jpeg") {
						$result = $this->checkJpegOrientation($this->images[$i]["file"], $size);
						$this->images[$i]["file"] = $result["file"];
						$size = $result["size"];
					}
					$this->images[$i]["w"] = $size[0];
					$this->images[$i]["h"] = $size[1];
					$this->images[$i]["mime"] = $size["mime"];
				}
			}
		}
	}

	protected function checkJpegOrientation($image, $size)
	{
		$outputFile = $image;
		$exif = exif_read_data($image);

		// Calculating required angle rotation
		if(!empty($exif["Orientation"])) {
			switch ($exif["Orientation"]) {
				case 3:
					$angle = 180;
					break;
				case 6:
					$angle = -90;
					break;
				case 8:
					$angle = 90;
					break;
				default:
					$angle = null;
			}

			// Rotate if rotation is required
			if(!is_null($angle)) {
				$original = imagecreatefromjpeg($image);
				$rotated = imagerotate($original, $angle, 0);

				// Save the rotation file with new name
				$extension = pathinfo($image, PATHINFO_EXTENSION);
				$outputFile = str_replace(".$extension", "_rotated.jpg", $image);
				imagejpeg($rotated, $outputFile, 100);

				// Get the Dimention of the new rotated file
				$size = getimagesize($outputFile);
				imagedestroy($outpuFile);
				imagedestroy($rotated);
			}
		}

		return ["file" => $outputFile, "size" => $size];
	}

	protected function calculateRatios()
	{
		foreach ($this->images as $i => $image) {
			$this->images[$i]["ratios"] = [];
			if ($this->images[$i]["h"] > $this->images[$i]["w"]) {
				$divisor = $this->images[$i]["h"];
			} else {
				$divisor = $this->images[$i]["w"];
			}
			foreach ($this->outputSizes as $outputSize) {
				$ratio = $outputSize / $divisor;
				$this->image[$i]["ratios"][] = $ratio > 1 ? 1 : $ratio;
			}
		}
	}

	protected function createImageResource($file, $type)
	{
		switch ($type) {
			case 'image/jpeg':
				return imagecreatefromjpeg($file);
			case 'image/png':
				return imagecreatefrompng($file);
			case 'image/gif':
				return imagecreatefromgif($file);
		}
	}

	protected function addWatermark(array $image, $resource)
	{
		// Calculating the co-ordinates for placing the watermark in the main image
		$x = $image["w"] - $this->wmarkW - $this->marginR;
		$y = $image["h"] - $this->wmarkH - $this->marginB;

		// Merging the watermark in the main image
		imagecopy($resource, $this->watermark, $x, $y, 0, 0, $this->wmarkW, $this->wmarkH);
	}

	protected function generateOutput($image, $resource)
	{
		// Store the outputSizes in temprory variable
		$storedSizes = $this->outputSizes;
		// Get the constitute parts of the current file name
		$nameParts = pathinfo($image["file"]);
		// Use imageScale() if supported
		if($this->useImageScale) {
			// Recalculate $outputSizes if the images's height is greater than its width.
			if(imagesy($resource) > imagesx($resource)) {
				$this->recalculateSizes($resource);
			}

			foreach ($this->outputSizes as $outputSize) {
				// Don't resize if current output size is greater than original
				if($outputSize >= $image["w"]) {
					continue;
				}
				$scaled = imagescale($resource, $outputSize, -1, $this->resample);
				$filename = $nameParts["filename"] . "_". $outputSize . "." . $nameParts["extension"];
				// Delegate file output to specialized method
				$this->outputFile($scaled, $image["mime"], $filename);
			}
		} else {
			/**
			 * Image resample for older version of php ( 5.4 or earlier)
			 *  Older version of php does not support imagescale() function
			 *  This code resizes image without using imagescale() function
			 */
			foreach ($image["ratios"] as $ratio) {
				$w = round($image["w"] * $ratio);
				$h = round($image["h"] * $ratio);
				$filename = $nameParts["filename"] . "_" . $outputSize . "." . $nameParts["extention"];

				$scaled = imagecreatetruecolor($w, $h);
				imagecopyresampled($scaled, $resource, 0, 0, 0, 0, $w, $h, $image["w"], $image["h"]);
				$this->outputFile($scaled, $image["mime"], $filename);
			}
		}

		// Reassigning temproray stored sizes to the $outputSizes property
		$this->outputSizes = $storedSizes;
	}

	protected function recalculateSizes($resource)
	{
		// Get the widht and height of the image resource
		$w = imagesx($resource);
		$h = imagesy($resource);

		foreach ($this->outputSizes as &$size) {
			$size = round($size * $w / $h, -1);
		}
	}

	protected function outputFile($scaled, $type, $name)
	{
		$success = false;
		$outputFile = $this->destination . DS . $name;

		switch ($type) {
			case 'image/jpeg':
				// echo "<br>Matched $type<br>";
				$success = imagejpeg($scaled, $outputFile, $this->jpegQuality);
				break;
			case 'image/png':
				$success = imagepng($scaled, $outputFile, $this->pngCompression);
				break;
			case 'image/gif':
				$success = imagegif($scaled, $outputFile);
		}

		imagedestroy($scaled);
		if($success) {
			$this->generated[] = $outputFile;
		}
	}

}
