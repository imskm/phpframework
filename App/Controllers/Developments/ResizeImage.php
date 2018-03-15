<?php
	function index()
	{
		$source = dirname(dirname(__DIR__)) . "/public/assets/img/source";
		$wmark_src = dirname(dirname(__DIR__)) . "/public/assets/img/source/wm.png";
		$output_images = dirname(dirname(__DIR__)) . "/public/assets/img/output1";
		$images = new \FilesystemIterator($source);
		$images = new \RegexIterator($images, "/(?<!watermark|_rotated)\.(jpg|png|gif)$/");
		$originals = [];

		foreach ($images as $image) {
			$originals[] = $image->getFilename();
		}

		// print_r($originals);
		try {
			$resize = new Image($originals, $source);
			$resize->setOutputSizes([400, 500, 600, 850]);
			$resize->watermark($wmark_src);
			$result = $resize->outputImages($output_images);

			if($result["output"]) {
				echo "<p>The following images were generated successfully.</p>";
				echo "<ul>";
				foreach ($result["output"] as $output) {
					echo "<li>$output</li>";
				}
				echo "</ul>";
			}
			if($result["invalid"]) {
				echo "<p>The following files were invalid.</p>";
				echo "<ul>";
				foreach ($result["invalid"] as $invalid) {
					echo "<li>$invalid</li>";
				}
				echo "</ul>";
			}

		} catch(Exception $e) {
			echo $e->getMessage();
		}

/* OLD code testing for image resizing.
		$original = dirname(dirname(__DIR__)) . "/public/assets/img/sadaf.jpg";
		$destination = dirname(dirname(__DIR__)) . "/public/uploads/";
		$wmark_src = dirname(dirname(__DIR__)) . "/public/assets/img/wm.png";
		$sizes = [
			"small" => 200,
			"large" => 600
		];

		$resource = imagecreatefromjpeg($original);

		foreach ($sizes as $name => $size) {
			$scaled = imagescale($resource, $size);
			imagejpeg($scaled, $destination . "image_" . $name . ".jpg", 60);
			imagedestroy($scaled);
		}

		imagedestroy($resource);

		// /**
		//  * Image resample for older version of php ( 5.4 or earlier)
		//  *  Older version of php does not support imagescale() function
		//  *  This code resizes image without using imagescale() function
		//  *                              +
		//  *  Embedding watermark in original image
		//
		$resource = imagecreatefromjpeg($original);

		// Dimensions of Original image
		$w = imagesx($resource);
		$h = imagesy($resource);

		// Dimensions of Watermark image
		$wmark_w = 640;
		$wmark_h = 187;
		$margin_bottom = $margin_right = 50;

		// Creating image resource for watermark image
		$watermark = imagecreatefrompng($wmark_src);

		// Merging the watermark into main image
		imagecopy($resource, $watermark, $w - $wmark_w - $margin_right, $h - $wmark_h - $margin_bottom, 0, 0, $wmark_w, $wmark_h);

		// Creating empty image resource for Watermark
		//$wmark_resource = imagecreatetruecolor($wmark_w, $wmark_h);
		//imagefilledrectangle($wmark_resource, 0, 0, $wmark_w, $mark_h, 0x000000);

		$ratios = [
 			"small" => 400/$w,
 			"large" => 800/$w,
 			"large@2" => 1600/$w
 		];

		foreach ($ratios as $name => $ratio) {
			$rs_w = $w * $ratio;
			$rs_h = round($h * $ratio);

			$output = imagecreatetruecolor($rs_w, $rs_h);
			// Quality of imagecopyresized() is bad
			//imagecopyresized($output, $resource, 0, 0, 0, 0, $rs_w, $rs_h, $w, $h);
			imagecopyresampled($output, $resource, 0, 0, 0, 0, $rs_w, $rs_h, $w, $h);
			imagejpeg($output, $destination . "imag_sadaf_rswm_60_" . $name . ".jpg", 60);
			imagedestroy($output);
		}
		imagedestroy($resource);
		imagedestroy($watermark);
*/
		echo "<p>Done image scaling";
		echo "<p>Hello from index action of Image controller.";
	}
