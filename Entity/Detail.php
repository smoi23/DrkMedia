<?php
namespace DrkMedia\Entity;


use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Entity
 * @Table(name="detail")
 */
class Detail
{
	/**
	 * @Id
	 * @Column(type="integer")
	 * @GeneratedValue
	 * @GeneratedValue(strategy="AUTO")
     */	
	protected $id;
	/** 
     * @ManyToOne(targetEntity="MediaEntity", inversedBy="tags")
     * @ORM\JoinColumn(name="media_id", referencedColumnName="id", onDelete="CASCADE")
	 */
	protected $media;
	/** @Column(length=128) */
	protected $name;
	/** @Column(length=128) */
	protected $nameFirst;
	/** @Column(length=128) */
	protected $position;
	/** @Column(length=128) */
	protected $role;	
		
	protected  $blacklist = array(	0=>'blacklist',
									1=>'media');
	
	public function getId() {
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
		
	public function getMedia() {
		return $this->media;
	}
	
	public function setMedia($media)
	{
		$this->media = $media;
	}

	
	public function getName() {
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getNameFirst() {
		return $this->nameFirst;
	}

	public function setNameFirst($nameFirst)
	{
		$this->nameFirst = $nameFirst;
	}

	public function getPosition() {
		return $this->position;
	}
	
	public function setPosition($pos)
	{
		$this->position = $pos;
	}
	
	public function getRole() {
		return $this->role;
	}
	
	public function setRole($role)
	{
		$this->role = $role;
	}

	public function getAuthor() {
		return $this->author;
	}
	
	public function setAuthor($author)
	{
		$this->author = $author;
	}
	
	public function getPropertyArray()
	{
		$props = get_object_vars($this);
		$this->filterBlackList($props);
		return $props;
	
	}

	public function import(Detail $i_object)
	{
		foreach (get_object_vars($i_object) as $key => $value)
		{
			if ($value != null && !is_object($value) && !is_array($value))
			{
				$this->$key = $value;
			}
		}
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
