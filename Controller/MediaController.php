<?php
namespace DrkMedia\Controller;

use Symfony\Component\Validator\Constraints\Null;


use DrkMedia\Form\MediaType;
use DrkMedia\Entity\MediaEntity;
use DrkMedia\Lib\MetaData;

use Silex\Application;
// use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\File\File;



//class MediaController implements ControllerProviderInterface
class MediaController
{

	public function indexAction(Request $request, Application $app)
	{

		$query = $app['orm.em']->createQueryBuilder()
		->addSelect('media.id')
		->addSelect('media.filename')
		->addSelect('media.publicDate')
		->from('DrkMedia\Entity\MediaEntity','media')
		->setFirstResult( 0 )
		->setMaxResults(10)
		->getQuery();
		
		
		/*
		$query = $app['orm.em']->createQueryBuilder()
		->select('filename')
		->from('media', 'm')
		->orderBy('m.id', 'ASC')
		->setParameter(1, 100)
		->getQuery();
		*/
		
		$props = $query->getResult();
		
		
		$data = array(
				'props' => $props,
		);
		
		return $app['twig']->render('list.html.twig', $data);
				
	}
	
	

	public function viewAction(Request $request, Application $app, $id)
	{
		
		$media = $app['orm.em']->getRepository('DrkMedia\Entity\MediaEntity')->find($id);
			
		$props = $media->getPropertyArray();
				
		$data = array(
				'media' => $media,
				'props' => $props,
		);
		
		return $app['twig']->render('media.html.twig', $data);

		
	}

	public function newAction(Request $request, Application $app)
	{

		$filename = $request->query->get('filename');
			
		if (!$filename)
		{
			return "asd";
		}
		
		
		$media = new MediaEntity();
		

		$media->setFilename($filename);
		
		// populate with metadata
		$metaData = new MetaData(WEB_DIRECTORY.'/stage/'.$filename);
		$metaData->parse();
		
		if ($metaData->fileInfo)
		{
			$media->parseFileInfo($metaData->fileInfo['comments']);
		}
				
		return $this->editAction($request, $app, NULL, $media);
		
		
	}
	
	
	public function editAction(Request $request, Application $app, $id=NULL, $media=NULL)
	{

		
		if ($id)
		{
			// get media from db				
		}
		
		$form = $app['form.factory']->create(new MediaType(), $media);
				
		if (1==0 && $request->isMethod('POST')) {
			$form->bind($request);
			if ($form->isValid()) {
				/*
				$app['repository.artist']->save($artist);
				$message = 'The artist ' . $artist->getName() . ' has been saved.';
				$app['session']->getFlashBag()->add('success', $message);
				// Redirect to the edit page.
				$redirect = $app['url_generator']->generate('admin_artist_edit', array('artist' => $artist->getId()));
				
				return $app->redirect($redirect);
				*/
			}
		}

		$data = array(
				'form' => $form->createView(),
				'title' => 'Info',
				'post_url' => 'media',
		);
		
		return $app['twig']->render('form.html.twig', $data);

	}
	
	
	public function proceedAction(Request $request, Application $app)
	{
		
		$media = new MediaEntity();

		$form = $app['form.factory']->create(new MediaType(), $media);
		$form->handleRequest($request);
		
		
		$fileSource = WEB_DIRECTORY.'/stage/'.$media->getFilename();

						
		// move file to upload location
		$file = new File($fileSource);
		$file->move(WEB_DIRECTORY.'/upload/');
		
		// insert in db
		$app['orm.em']->persist($media);
		$app['orm.em']->flush();
			
		
		return $this->viewAction($request, $app, $media->getId());
	}

}


