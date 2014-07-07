<?php
namespace DrkMedia\Controller;


use Symfony\Component\Validator\Constraints\Null;


use DrkMedia\Form\DetailType;
use DrkMedia\Entity\Detail;
use DrkMedia\Form\MediaType;
use DrkMedia\Entity\MediaEntity;
use DrkMedia\Lib\MetaData;

use Silex\Application;
// use Silex\ControllerProviderInterface;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpFoundation\File\File;

use Doctrine\Common\Collections\ArrayCollection;

//class MediaController implements ControllerProviderInterface
class MediaController
{

	public function indexAction(Request $request, Application $app)
	{

		$query = $app['orm.em']->createQueryBuilder()
		->addSelect('media.id')
		->addSelect('media.filename')
		->addSelect('media.published')
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

		if (!$media)
		{
			throw $this->createNotFoundException('Unable to find Media post.');
		}
		
		
		
		$details = array();
		foreach ($media->getTags() as $tag)
		{
			$details[] = $tag->getPropertyArray();				
		}
		
		$props = $media->getPropertyArray();
				
		$data = array(
				'media' => $media,
				'props' => $props,
				'details' => $details,
		);
		
		return $app['twig']->render('media.html.twig', $data);	
	}

	public function newAction(Request $request, Application $app)
	{	
		$filename = $request->query->get('filename');

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
			// echo "<p>Get from DB</p>";
			$media = $app['orm.em']->getRepository('DrkMedia\Entity\MediaEntity')->find($id);
			// $media->initialize();
			
			if (!$media) 
			{
				throw $this->createNotFoundException('Unable to find Media post.');
			}			
		
		}

		$form = $app['form.factory']->create(new MediaType(), $media);	
		
		$data = array(
				'form' => $form->createView(),
				'title' => $media->getFilename(),
				'post_url' => $app['url_generator']->generate('media/post'),
		);
		
		return $app['twig']->render('edit.html.twig', $data);
	}
	
	
	public function proceedAction(Request $request, Application $app)
	{
		$media = new MediaEntity();

		$form = $app['form.factory']->create(new MediaType(), $media);
		$form->handleRequest($request);

		// set modified time
		$media->setModified(new \DateTime() );

		// more checks, if $form->isValid(), ..
		
		
		if ($media->getId() > 0)
		{
			$media_db = $app['orm.em']->getRepository('DrkMedia\Entity\MediaEntity')->find($media->getId());
	
			if ( $form->get('Remove(!)')->isClicked() )
			{
				// echo "<p>remove</p>";				
				$app['orm.em']->remove($media_db);
				$app['orm.em']->flush();
				return $this->indexAction($request, $app );
				
			}
			else
			{
				// echo "<p>update</p>";
	// 			$media_db->import($media);
	// 			$media_db->updateTags();

				// remove details
				foreach ($media_db->getTags() as $tag)
				{
					//if (false === $media->getTags()->contains($tag))
					if (!$media->getTagById($tag->getId())) // TODO: cheesy ??
					{
						echo "<p>not in media ".$tag->getId()."</p>";
						$media_db->removeTag($tag);
						$app['orm.em']->remove($tag);
					}
				}
				$app['orm.em']->flush();
				
				$create_time = $media_db->getCreated();
				$app['orm.em']->merge($media);		
				$media_db->importTags($media);			
				$media_db->setCreated($create_time);
				
				$app['orm.em']->flush();
			}
		}
		else
		{		
			// echo "<p>new</p>";
			$fileSource = WEB_DIRECTORY.'/stage/'.$media->getFilename();
	
			// set creation time
			$media->setCreated(new \DateTime() );
							
			// move file to upload location
			$file = new File($fileSource);
			$file->move(WEB_DIRECTORY.'/data/');
			
			// insert in db
			$app['orm.em']->persist($media);			
			try 
			{
				$app['orm.em']->flush();
			} catch(Exception $e)
			{
				$m = $e->getMessage();
				echo $m . "<br />\n";
			}
			
		}

		return $this->viewAction($request, $app, $media->getId());
	}

	
	private function proceedRemoveAction( $media )
	{
		
	}
	
}


