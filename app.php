<?php
require_once __DIR__.'/../../vendor/autoload.php';

define('WEB_DIRECTORY', __DIR__.'/../../web');

use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;


use Silex\Provider\FormServiceProvider;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\User;


use Doctrine\DBAL\Schema\Table;
use Doctrine\Common\Cache\ApcCache;
use Doctrine\Common\Cache\ArrayCache;

use Dflydev\Silex\Provider\DoctrineOrm\DoctrineOrmServiceProvider;

$app = new Silex\Application();

$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
		'db.options' => array(
				'dbname' => 'rwm',
				'user' => 'root',
				'password' => 'root23',
				'host' => '127.0.0.1',
				'driver' => 'pdo_mysql',
		),
));



$app->register(new DoctrineOrmServiceProvider, array(
		"orm.proxies_dir" => __DIR__ . '/../../cache/doctrine/proxy',
		"orm.em.options" => array(
				"mappings" => array(
						// Using actual filesystem paths
						array(
								"type" => "annotation",
								"namespace" => "DrkMedia\Entity",
								"path" => __DIR__."/../../src/DrkMedia/Entity",
						),
						array(
								"type" => "xml",
								"namespace" => "Bat\Entities",
								"path" => __DIR__."/src/Bat/Resources/mappings",
						),
						// Using PSR-0 namespaceish embedded resources
						// (requires registering a PSR-0 Resource Locator
						// Service Provider)
						/*
						array(
								"type" => "annotation",
								"namespace" => "Baz\Entities",
								"resources_namespace" => "Baz\Entities",
						),
						array(
								"type" => "xml",
								"namespace" => "Bar\Entities",
								"resources_namespace" => "Bar\Resources\mappings",
						),
						*/
				),
		),
));



$app->register(new FormServiceProvider()); // has to be before twig ! o_o

$app->register(new Silex\Provider\ValidatorServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
		'translator.domains' => array(),
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app->register(new Silex\Provider\TwigServiceProvider(), array(
		'twig.path' => __DIR__.'/Resources/views',
		'twig.form.resources'   => __DIR__.'/Resources/form',
));

// 'twig.form.templates' => array(__DIR__.'/Resources/form/form_edit_layout.html.twig'),


// prevent weird error
function dummy_trans($str) {
	return $str;
}
$app['twig']->addFilter('trans*', new Twig_Filter_Function('dummy_trans'));

$app['debug'] = true;

return $app;
