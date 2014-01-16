<?php
/**
*
* @package symBB
* @copyright (c) 2013-2014 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\ExtensionBundle;

use \SymBB\ExtensionBundle\Exception\PackagistNotFound;
use \SymBB\ExtensionBundle\KernelPlugin;
use \SymBB\ExtensionBundle\Extension;

class Api {
     
    protected $packagistClient;
    protected $extensions       = array();

    public function __construct()
    {
       $this->packagistClient   = new \Packagist\Api\Client();
       $this->extensions        = KernelPlugin::getExtensions();
    }
    
    public function addExtension($packageName, $version, $versionConstraint = ''){
    
        $package = $this->packagistClient->get($packageName);
        
        if(\is_object($package)){
            
            $repo       = $package->getRepository();
            
            var_dump($repo);
            
            $extension = new Extension();
            $extension->setPackage($packageName);
            $extension->setName($package->getName());
            $extension->setVersion($version);
            $extension->setVersionConstraint($versionConstraint);
            $extension->disabled();
            
        } else {
            throw new PackagistNotFound('Extension Package not found in Packagist!');
        }
        
    }
    
}
    