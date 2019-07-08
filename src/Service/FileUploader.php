<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use ZipArchive; 

class FileUploader
{
	private $targetDir;
	
	public function __construct($targetDir)
	{
		$this->targetDir = $targetDir;
	}
	
	public function upload(UploadedFile $file)
	{
		// generate a unique name for the file before saving it
		$fileName = md5(uniqid()).'.'.$file->guessExtension();
		
		$file->move($this->targetDir, $fileName);
		
		return $fileName;
	}

	public function uploads($files, $folder)
	{	
		foreach($files as $file) {

			// generate a unique name for the file before saving it
			$fileName = md5(uniqid()) . '.' .$file->guessExtension();
			
			$fileNames[] = $fileName;

			$file->move($this->targetDir.'/'.$folder, $fileName);
		}
		return $fileNames;
	}
	
	public function removeUpload($filename)
	{
		unlink($this->targetDir.'/'.$filename);
	}
	
	public function getTargetDir()
	{
		return $this->targetDir;
	}

	
	public function extractTo($destination, $folder)
	{
		$zip = new ZipArchive;
		if ($zip->open($folder) === TRUE) {
			
			$zip->extractTo($destination);
			$zip->close();
			return true;
		} else {
			return false;
		}
	}


	/* creates a compressed zip file */
	public function createZip($files = array(),$destination = '',$overwrite = false, $folder) {
		
		//if the zip file already exists and overwrite is false, return false
		if(file_exists($destination) && !$overwrite) { return false; }

		//vars
		$valid_files = array();
		//if files were passed in...
		if(is_array($files)) {
			//cycle through each file
			foreach($files as $file) {
				//make sure the file exists
				if(file_exists($file)) {
					$valid_files[] = $file;
				}
			}
		}

		//if we have good files...
		if(count($valid_files)) {
			//create the archive
			$zip = new \ZipArchive();
			if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return 1;
			}
			//add the files
			foreach($valid_files as $file) {
				//$tofolder = str_replace("upload/photo_service/".$folder,"", $file);
				$zip->addFile($file->getPathname(),$file->getClientOriginalName());
			}
			//debug
			//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;
			
			//close the zip -- done!
			$zip->close();
			
			//check to make sure the file exists
			return file_exists($destination);
		}
		else
		{
			return false;
		}
	}
}
