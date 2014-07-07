<?php
namespace DrkMedia\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\HttpKernel\HttpKernelInterface;

use Symfony\Component\Validator\Constraints as Assert;

class UploadController
{
		
	public function indexAction(Request $request, Application $app)
	{
			
		$data = array(
				'file' => '',
		);
		
		$form = $app['form.factory']->createBuilder('form', $data)
		->add('file', 'file', array('required'=>true, 'constraints' => new Assert\File( array('maxSize'=>'60M') )))
		->getForm();
		
		
		if ($request->isMethod('POST'))
		{
			$form->bind($request);
			if ($form->isValid()) 
			{
				$files = $request->files->get($form->getName());
				$path = WEB_DIRECTORY.'/stage/';
				$filename = $files['file']->getClientOriginalName();
				$files['file']->move($path,$filename);
								
				// create url direct
				//$url = $request->getUriForPath('/media/new');				
				//$subRequest = Request::create($url, 'GET', array('filename'=>$filename) );

				// If you are using UrlGeneratorProvider, you can also generate the URI:
				$subRequest = Request::create($app['url_generator']->generate('media/new'), 'GET', array('filename'=>$filename) );
                $subRequest->server->set('SCRIPT_NAME', $app['request']->server->get('SCRIPT_NAME'));
                $subRequest->server->set('SCRIPT_FILENAME', $app['request']->server->get('SCRIPT_FILENAME'));
				return $app->handle($subRequest, HttpKernelInterface::SUB_REQUEST);				
			}	
		}
		
		return $app['twig']->render('upload.html.twig', array(	'form' => $form->createView(),
																'title' => 'Upload',
																'post_url' => $app['url_generator']->generate('upload'),
		));
			
	}	
	
	
	public function uploadAction(Request $request, Application $app)
	{
		return "uploadAction";
	}
		
	
}