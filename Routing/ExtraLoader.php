<?php
/**
 *
 * @package Symbb
 * @copyright (c) 2013-2014 Christian Wielath
 * @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
 *
 */

namespace Symbb\ExtensionBundle\Routing;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Config\FileLocator;

class ExtraLoader implements LoaderInterface
{

    private $loaded = false;

    protected $root = '';

    public function __construct($kernel)
    {
        $this->root = $kernel->getRootDir();
    }

    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $extensions = \Symbb\ExtensionBundle\Api::getExtensions();

        $routes = new RouteCollection();

        foreach ($extensions as $extension) {
            if ($extension->isEnabled() && $extension->hasRouting()) {
                $bundleDir = $extension->getDir();
                $fileLocator = new FileLocator($bundleDir . '/Resources/config');
                $file = $bundleDir . '/Resources/config/routing.yml';
                if (\is_file($file)) {
                    $routingLoader = new \Symfony\Component\Routing\Loader\YamlFileLoader($fileLocator);
                    $newCollection = $routingLoader->load('routing.yml');
                    $routes->addCollection($newCollection);
                }
            }
        }

        $this->loaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'extra' === $type;
    }

    public function getResolver()
    {
        // needed, but can be blank, unless you want to load other resources
        // and if you do, using the Loader base class is easier (see below)
    }

    public function setResolver(LoaderResolverInterface $resolver)
    {
        // same as above
    }
}
