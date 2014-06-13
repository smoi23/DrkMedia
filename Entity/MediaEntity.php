<?php
namespace DrkMedia\Entity;


use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Entity
 * @Table(name="media")
 */
class MediaEntity
{
	/**
	 * 	@Id
	 *  @Column(type="integer") 
	 *  @GeneratedValue
	 *  @GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	/** @Column(length=128) */
	protected $filename;
	/** @Column(length=128) */
	protected $name;
	/** @Column(length=128) */
	protected $titleA;
	/** @Column(length=128) */
	protected $titleB;
	/** @Column(length=128) */
	protected $subTitle;
	/** @Column(length=128) */
	protected $wwv;
	/** @Column(length=128) */
	protected $album;
	/** @Column(length=128) */
	protected $work;
	/** @Column(type="smallint") */
	protected $trackNumber;
	/** @Column(type="smallint") */
	protected $trackNumberMax;
	/** @Column(type="smallint") */
	protected $albumNumber;
	/** @Column(type="smallint") */
	protected $albumNumberMax;
	/** @Column(type="smallint") */
	protected $signature;
	/** @Column(type="datetime")*/
	protected $time;
	/** @Column(length=128) */
	protected $label;
	/** @Column(length=128) */
	protected $location;
	/** 
	 * @Column(type="date")
	 * @Assert\Date()
	 * */
	protected $publicDate;
	
	
	protected  $blacklist = array(	0=>'blacklist',
									1=>'time',
									2=>'publicDate');
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
		
	public function getFilename()
	{
		return $this->filename;
	}
	
	public function setFilename($filename)
	{
		$this->filename = $filename;
	}
	
	public function getName() {
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getTitleA() {
		return $this->titleA;
	}
	
	public function setTitleA($title)
	{
		$this->titleA = $title;
	}
	
	public function getTitleB() {
		return $this->titleB;
	}
	
	public function setTitleB($title)
	{
		$this->titleB = $title;
	}
	
	public function getSubTitle() {
		return $this->subTitle;
	}
	
	public function setSubTitle($title)
	{
		$this->subTitle = $title;
	}
	
	
	public function getWwv() {
		return $this->wwv;
	}
	
	public function setWwv($wwv)
	{
		$this->wwv = $wwv;
	}
	
	public function getAlbum() {
		return $this->album;
	}
	
	public function setAlbum($album)
	{
		$this->album = $album;
	}
	
	public function getWork() {
		return $this->work;
	}
	
	public function setWork($work)
	{
		$this->work = $work;
	}
	
	public function getTrackNumber() {
		return $this->trackNumber;
	}
	
	public function setTrackNumber($number)
	{
		$this->trackNumber = $number;
	}
	
	public function getTrackNumberMax() {
		return $this->trackNumberMax;
	}
	
	public function setTrackNumberMax($number)
	{
		$this->trackNumberMax = $number;
	}
	
	public function getAlbumNumber() {
		return $this->albumNumber;
	}
	
	public function setAlbumNumber($number)
	{
		$this->albumNumber = $number;
	}
	
	public function getAlbumNumberMax() {
		return $this->albumNumberMax;
	}
	
	public function setAlbumNumberMax($number)
	{
		$this->albumNumberMax = $number;
	}
	
	public function getSignature() {
		return $this->signature;
	}
	
	public function setSignature($signature)
	{
		$this->signature = $signature;
	}
	
	public function getTime() {
		return $this->time;
	}
	
	public function setTime($time)
	{
		$this->time = $time;
	}

	public function getLabel() {
		return $this->label;
	}
	
	public function setLabel($label)
	{
		$this->label = $label;
	}

	public function getLocation() {
		return $this->location;
	}
	
	public function setLocation($loc)
	{
		$this->location = $loc;
	}
	
	public function getPublicDate() {
		return $this->publicDate;
	}
	
	public function setPublicDate($date)
	{
		$this->publicDate = $date;
	}
	
	public function parseFileInfo($fileInfo)
	{
		// var_dump($fileInfo);
		// echo $fileInfo['album'][0];
		
		if (isset($fileInfo['album']))
		{
			echo $fileInfo['album'];
			$this->album = $fileInfo['album'][0];
		}
	}

	public function getPropertyArray()
	{
		$props = get_object_vars($this);
		$this->filterBlackList($props);
		return $props;
		
	}	
	
	private function filterBlackList(&$i_array)
	{
		foreach( $i_array as $key=>$val )
		{
			if (in_array($key, $this->blacklist))
			{
				unset($i_array[$key]);
			}
		}
	}
	
	
}