<?php
namespace DrkMedia\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
// use \Symfony\Component\Validator\Constraints\NotBlank;

class MediaType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{	
		$builder
		->add('id', 'hidden')
		->add('filename', 'hidden')	
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
		->add('time', 'time', array('constraints' => new Assert\Time()))
		->add('label', 'text')
		->add('location', 'text')
		->add('published', 'date', array('constraints' => new Assert\Date()))
		->add('tags', 'collection', array('type' => new DetailType(), 'allow_add' => true, 'allow_delete' => true, 'prototype' => true, 'by_reference' => false))
		->add('Save', 'submit')
		->add('Remove(!)', 'submit');		
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'DrkMedia\Entity\MediaEntity'
		));
	}	
	
	public function getName()
	{
		return 'mediaform';
	}
		
}
