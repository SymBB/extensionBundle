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
    
    public function addExtension($githubUser, $githubRepo, $version, $versionConstraint = ''){
    
        $client = new \Github\Client();
        $repo = $client->api('repo')->show($githubUser, $githubRepo);
        var_dump($repo);
        
        $packageName = $githubUser.'/'.$githubRepo;
        $packageName = \strtolower($packageName);
        
        $package = $this->packagistClient->get($packageName);
        
        if(\is_object($package)){
            
            $repo           = $package->getRepository();
            $repo           = \str_replace('.git', '/master', $repo);
            $repo           = \str_replace('git://', 'https://raw.', $repo);
            $compsoerUrl    = $repo.'/composer.json';
            
            $composerContent = \file_get_contents($compsoerUrl);
            $composerContent = \json_decode($composerContent, true);
            
            
            $nameSpace = key($composerContent['autoload']['psr-0']);
            var_dump($nameSpace);
            
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
    