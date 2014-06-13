<?php
namespace DrkMedia\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

// use \Symfony\Component\Validator\Constraints\NotBlank;

class MediaType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{	
		$builder->add('id', 'integer')
		->add('filename')	
		->add('name', 'text')
		->add('titleA', 'text')
		->add('titleB', 'text')
		->add('subTitle', 'text')
		->add('wwv', 'text')
		->add('album', 'text')
		->add('work', 'text')
		->add('trackNumber', 'integer', array('data' => 2))
		->add('trackNumberMax', 'integer', array('data' => 23))
		->add('albumNumber', 'integer', array('data' => 1))
		->add('albumNumberMax', 'integer', array('data' => 11))
		->add('signature', 'text', array('data' => 'default'))
		->add('time', 'datetime', array('constraints' => new Assert\DateTime()))
		->add('label', 'text')
		->add('location', 'text')
		->add('publicDate', 'date', array('constraints' => new Assert\Date()));
		
	}
	
	public function getName()
	{
		return 'mediaform';
	}
		
}
