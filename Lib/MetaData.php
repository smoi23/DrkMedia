<?php
namespace DrkMedia\Lib;

require_once(WEB_DIRECTORY.'/../getID3-1.9.8/getid3/getid3.php');


class MetaData
{
	private $filesrc;
	private $PageEncoding = 'UTF-8';
	private $getID3;
	public $fileInfo = NULL;
	
	function __construct($filesrc)
	{
		$this->filesrc = $filesrc;	
		
		// Initialize getID3 engine
		$this->getID3 = new \getID3;
		$this->getID3->setOption(array('encoding' => $this->PageEncoding));
		
	}
	
	
	public function parse()
	{
		$this->fileInfo = $this->getID3->analyze($this->filesrc);
		\getid3_lib::CopyTagsToComments($this->fileInfo);
		\getid3_lib::ksort_recursive($this->fileInfo);
		
		//var_dump($ThisFileInfo);
		
	}
	
}

?>
