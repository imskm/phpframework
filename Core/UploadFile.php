<?php

namespace Core;

/**
 * Upload Files be it Image, text, video, etc.
 * class for handling aal kind of uploads.
 *
 * v-1 : 14-07-2017
 */
class UploadFile
{
	protected $destination;
	protected $fileName;
	protected $userProvidedFileName;
	protected $errors = array();
	protected $maxSize = 102400;		// 153600 bytes = 150Kb
	protected $permittedType = array(
		'image/jpeg',
		'image/pjpeg',
		'image/gif',
		'image/png',
		'image/webp'
	);
	protected $multipleFiles = false;
	protected $increment = 0;
	protected $userProvidedExt;

	public function __construct($destination)
	{
		if(!is_dir($destination))
			throw new \Exception("$destination must be valid directory name.");
		if(!is_writable($destination))
			throw new \Exception("$destination must be writable directory.");

		// adding trailing '/' if it is not there
		if($destination[strlen($destination) - 1] != '/')
			$destination .= '/';

		$this->destination = $destination;
		$this->fileName = null;
		$this->userProvidedFileName = null;

	}

	public function upload()
	{
		$uploaded = current($_FILES);
		$flag = true;	// Let file upload is successful

		if(is_array($uploaded["name"]))
		{
			$this->setMultipleFiles();
			foreach ($uploaded["name"] as $key => $value)
			{
				$currentFile["name"] = $uploaded["name"][$key];
				$currentFile["type"] = $uploaded["type"][$key];
				$currentFile["tmp_name"] = $uploaded["tmp_name"][$key];
				$currentFile["error"] = $uploaded["error"][$key];
				$currentFile["size"] = $uploaded["size"][$key];

				if(!$this->hasError($currentFile))
				{
					if(!$this->moveFile($currentFile))
						$flag = false;
				}
			}
		}
		else
		{
			if(!$this->hasError($uploaded))
			{
				if(!$this->moveFile($uploaded))
					$flag = false;
			}
		}

		return $flag;
	}

	protected function hasError($file)
	{
		if($file["error"] != 0)
		{
			$this->getErrorMessage($file);
			return true;
		}

		if(!$this->checkSize($file))
			return true;

		if(!$this->checkType($file))
			return true;

		$this->checkName($file);

		return false;
	}

	protected function getErrorMessage($file)
	{
		switch($file["error"])
		{
			case 1:
			case 2:
				$this->errors[] = $file['name'] . ' is too large.';
				break;
			case 3:
				$this->errors[] = $file['name'] . ' is partially uploaded.';
				break;
			case 4:
				$this->errors[] = 'No file submitted.';
				break;
			default :
				$this->errors[] = 'Something went wrong.';
				break;
		}
	}

	protected function moveFile($file)
	{
		$result = move_uploaded_file($file["tmp_name"], $this->destination . $this->fileName);
		if($result)
		{
			$this->errors[] = $file["name"] . " is uploaded successfully!";
			return true;
		}
		else
		{
			$this->errors[] = $file["name"] . " file upload failed!";
			return false;
		}
	}


	protected function checkSize($file)
	{
		if($file["size"] === 0 )
		{
			$this->errors[] = $file["name"] . " file is empty";
			return false;
		}
		elseif($file["size"] > $this->maxSize)
		{
			$this->errors[] = $file["name"] . " file size exceeds max limit.";
			return false;
		}
		else
		{
			return true;
		}
	}

	protected function checkName($file)
	{
		$nospace = str_replace(" ", "_", $file["name"]);
		if($this->multipleFiles)
		{
			if(is_null($this->userProvidedFileName))
			{
				$this->fileName = $nospace;		// file name is not set by user
			}
			else
			{
				$exname = explode(".", $this->userProvidedFileName);
				$this->fileName = $exname[0] . $this->appendNumber() . "." . $this->userProvidedExt;
			}
		}
		else
		{
			if(is_null($this->userProvidedFileName))
				$this->fileName = $nospace;		// file name is not set by user
			else
				$this->fileName = $this->userProvidedFileName;
		}
	}

	protected function checkType($file)
	{
		$imgcontent = getimagesize($file["tmp_name"]);
		$mimetype = $imgcontent["mime"];
		if (in_array($mimetype, $this->permittedType))
		{
			return true;
		}
		else
		{
			$this->errors[] = $file["type"] . " type is not permitted file type.";
			return false;
		}
	}

	public function getMessage()
	{
		return $this->errors;
	}

	public function setFileName($filename)
	{
		$filename = str_replace(" ", "_", $filename);
		$this->setExtension($filename);
		$this->userProvidedFileName = $filename;
	}

	public function setMaxSize($bytes)
	{
		if(is_numeric($bytes) && $bytes > 0)
			$this->maxSize = $bytes;
	}

	protected function appendNumber()
	{
		return ++$this->increment;
	}

	protected function setExtension($filename)
	{
		$exname = explode(".", $filename);
		$this->userProvidedExt = strtolower(end($exname));
	}

	protected function setMultipleFiles()
	{
		 $this->multipleFiles = true;
	}

}


 ?>
