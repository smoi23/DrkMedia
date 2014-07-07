<?php
namespace DrkMedia\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
// use \Symfony\Component\Validator\Constraints\NotBlank;

class DetailType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{	
// 		$resolver = new OptionsResolver();
// 		$this->configureOptions($resolver);
// 		$this->options = $resolver->resolve($options);
	
		$builder
		->add('id', 'hidden')
		->add('name', 'text')
		->add('nameFirst', 'text')
		->add('position', 'text')
		->add('role', 'text');	
	}

	
	
	/*
	protected function configureOptions(OptionsResolverInterface $resolver)
	{
		// ... configure the resolver, you will learn this
		// in the sections below
	}
	*/
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'DrkMedia\Entity\Detail',
				'position' => 'Singer',
		));
	}
	
	public function getName()
	{
		return 'detailform';
	}
		
}
