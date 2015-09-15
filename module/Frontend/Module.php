<?php
namespace Frontend;

use Frontend\Options\Exception\DirectoryNotWritableOrNotExistsException;
use Frontend\Options\Instagram\InstagramOptions;
use Frontend\Options\Exception\UndefinedException;
use Frontend\Options\ModuleOptions;
use Zend\Mvc\MvcEvent;

class Module
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        /* @var $sm \Zend\ServiceManager\ServiceManager */
        $sm = $e->getApplication()->getServiceManager();

        /* @var $config array */
        $config = $sm->get('config');

        if (!array_key_exists('instagram', $config) || empty($config['instagram'])) {
            throw new UndefinedException('Instagram config must be defined and must not be empty');
        }

        $instagramOptions = new InstagramOptions(isset($config['instagram']) ?
                                                 $config['instagram'] :
                                                 array());

        try {
            $moduleOptions = new ModuleOptions( isset( $config['frontend'] ) ?
                $config['frontend'] :
                array() );
        } catch (DirectoryNotWritableOrNotExistsException $ex) {
            print_r($ex->getMessage());
            exit(0);
        }

        /* @var $di \Zend\Di\Di */
        $di = $sm->get('di');
        $di->instanceManager()->addSharedInstance($instagramOptions,
                                                  'Frontend\Options\Instagram\InstagramOptions');

        $di->instanceManager()->addSharedInstance($moduleOptions,
                                                  'Frontend\Options\ModuleOptions');

        $di->instanceManager()->addSharedInstance($di, 'Zend\Di\Di');
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                )
            )
        );
    }
}
