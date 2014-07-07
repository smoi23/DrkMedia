<?php
namespace DrkMedia\Entity;


use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

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
	protected $id=0;
	/** @Column(length=128) */
	protected $filename;
	/** @Column(type="datetime")*/
	protected $created;
	/** @Column(type="datetime")*/
	protected $modified;
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
	/** @Column(type="time")*/
	protected $time;
	/** @Column(length=128) */
	protected $label;
	/** @Column(length=128) */
	protected $location;
	/** 
	 * @Column(type="date")
	 * @Assert\Date()
	 */
	protected $published;
	
	protected  $blacklist = array(	0=>'blacklist',
									1=>'created',
									2=>'modified',
									3=>'time',
									4=>'tags',
									5=>'published');
		
	/*
	 * orphanRemoval: Boolean that specifies if orphans, inverse OneToOne entities that are not connected to any owning instance, should be removed by Doctrine. Defaults to false.
	 */	
    /**
	 * @OneToMany(targetEntity="Detail", mappedBy="media", cascade={"persist", "remove"}, orphanRemoval=true)
     **/	
	protected $tags;
	
	public function __construct()
	{
		$this->initialize();		
	}
	
	public function initialize()
	{
		$this->tags = new ArrayCollection();
	}
	
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

	public function getCreated() {
		return $this->created;
	}
	
	public function setCreated($datetime)
	{
		$this->created = $datetime;
	}

	public function getModified() {
		return $this->modified;
	}
	
	public function setModified($datetime)
	{
		$this->modified = $datetime;
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
	
	public function getPublished() {
		return $this->published;
	}
	
	public function setPublished($date)
	{
		$this->published = $date;
	}
		
	public function getTags()
	{
		return $this->tags;
	}

	public function setTags(\Doctrine\Common\Collections\ArrayCollection $tags)
	{
		$this->tags = $tags;
		$this->updateTags();
	}
	
	
	public function addTag(Detail $tag)
	{
		$tag->setMedia($this);
		$this->tags->add($tag);
	}
	
	public function removeTag(Detail $tag)
	{
		$this->tags->removeElement($tag);	
	}	
	
	public function parseFileInfo($fileInfo)
	{
		// var_dump($fileInfo);
		// echo $fileInfo['album'][0];
		
		if (isset($fileInfo['album']))
		{
			$this->album = $fileInfo['album'][0];
		}
	}

	public function getPropertyArray()
	{
		$props = get_object_vars($this);
		
		$this->filterBlackList($props);
		
		return $props;
		
	}	
	
	public function import(MediaEntity $i_object)
	{
		foreach (get_object_vars($i_object) as $key => $value)
		{
			if ($value != null)
			{
				$this->$key = $value;
				if (is_object($value) && get_class($value)=="DateTime")
				{
					echo "<p>Update: ".$key." with: ".$value->format('Y-m-d H:i:s')."</p>";					
				}
				else
				{
					echo "<p>Update: ".$key." with: ".$value."</p>";
				}
			}
		}		
	}

	
	public function importTags(MediaEntity $i_media)
	{
		foreach ($i_media->getTags() as $tag)
		{
			if ($tag->getId() === null)
			{
				$_tag = null;				
			}
			else 
			{
				$_tag = $this->getTagById($tag->getId());
			}
						
			if ($_tag == null)
			{
				// echo "<p>Add tag: ".$tag->getId()."</p>";
				$this->addTag($tag);
			}
			else
			{
				// echo "<p>Update tag: ".$tag->getId()."</p>";
				$_tag->import($tag);
			}
		
		}
	}
		
	
	public function getTagById($_id)
	{
		foreach($this->tags as $tag)
		{
			if ($tag->getId() == $_id)
			{
				return $tag;
			}
		}
		return null;
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
	
	
	public function updateTags()
	{
		foreach($this->tags as $tag)
		{
			$tag->setMedia($this);
		}
	}	
	
}