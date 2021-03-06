<?php
namespace App\Service;

use \Gumlet\ImageResize;

class ImageResizer
{
	private $path;
	private $width;
	private $height;
	
	public function __construct($path, $width, $height)
	{
		$this->path = $path;
		$this->width = $width;
		$this->height = $height;
	}
	
	public function resize($fileName)
	{
		$pathname = $this->path.'/'.$fileName;
		$thumb = new ImageResize($pathname);
		
		$thumb->resizeToBestFit($this->width, $this->height);
		$thumb->save($pathname);
	}
	
	public function resizeMultiple($filesName, $folder)
	{
		foreach($filesName as $name) {
			$pathname = $this->path.'/'.$folder.'/'.$name;
			$thumb = new ImageResize($pathname);
			
			$thumb->resizeToBestFit($this->width, $this->height);
			$thumb->save($pathname);
		}
	}	
}
