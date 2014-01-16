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
use \Symfony\Component\Yaml\Parser;
use \Symfony\Component\Yaml\Dumper;

class Api {
    
    const EXTENSIONS_FILE = '/../../../../../app/config/extensions.yml';
     
    protected $packagistClient;
    protected $extensions       = array();

    public function __construct()
    {
       $this->packagistClient   = new \Packagist\Api\Client();
       $this->extensions        = KernelPlugin::getExtensions();
    }
    
    public static function getExtensionFilePath(){
        return __DIR__.self::EXTENSIONS_FILE;
    }
    
    public function add($githubUrl, $version = 'dev-master', $versionConstraint = ''){
    
        $githubUrlData      = \explode('/', $githubUrl);
        $githubRepo     = \array_pop($githubUrlData);
        $githubUser     = \array_pop($githubUrlData);
        $githubRepo     = \str_replace('.git', '', $githubRepo);
        
        $client         = new \Github\Client();
        $repoData       = $client->api('repo')->show($githubUser, $githubRepo);
        
        $repo           = $githubUrl;
        $repo           = \str_replace('https://', 'https://raw.', $repo);
        $compsoerUrl    = $repo.'/master/composer.json';

        $composerContent= \file_get_contents($compsoerUrl);
        $composerContent= \json_decode($composerContent, true);
        
        $packageName    = $composerContent['name'];
        
        $package        = $this->packagistClient->get($packageName);
        
        if(\is_object($package)){
            
            
            $nameSpace   = key($composerContent['autoload']['psr-0']);
            $class       = $nameSpace.'\\'.\str_replace('\\', '' , $nameSpace);
            
            $extension = new Extension();
            $extension->setPackage($packageName);
            $extension->setName($repoData['full_name']);
            $extension->setVersion($version);
            $extension->setVersionConstraint($versionConstraint);
            $extension->setBundleClass($class);
            $extension->disabled();
            
            $this->addExtension($extension);
            
        } else {
            throw new PackagistNotFound('Extension Package not found in Packagist!');
        }
        
    }
    
    public static function getExtensions(){
        $searchPath     = $this->getExtensionFilePath();
        $extensionList  = array();
        if(\is_file($searchPath)){
            $yaml           = new Parser();
            $extensionList  = $yaml->parse(file_get_contents($searchPath));
            $extensionList  = $extensionList;
        }
        return $extensionList;
    }
    
    public function disable($extensionName){
        $extensions = \SymBB\ExtensionBundle\KernelPlugin::getExtensions();
        $extensions[$extensionName]['enabled'] = false;
        $this->createFile($extensions);
    }
    
    public function enable($extensionName){
        $extensions = \SymBB\ExtensionBundle\KernelPlugin::getExtensions();
        $extensions[$extensionName]['enabled'] = true;
        $this->createFile($extensions);
    }
    
    public function remove($extensionName){
        $extensions = \SymBB\ExtensionBundle\KernelPlugin::getExtensions();
        unset($extensions[$extensionName]);
        $this->createFile($extensions);
    }
    
    public function addExtension(Extension $extension){
        $extensionData = $this->convertObjectToArray($extension);
        $extensions = \SymBB\ExtensionBundle\KernelPlugin::getExtensions();
        $extensions = $extensions + $extensionData;
        $this->createFile($extensions);
    }
    
    protected function createFile($extensions){
        $dumper = new Dumper();
        $yml = $dumper->dump($extensions);
        \file_put_contents(self::getExtensionFilePath(), $yml);
    }


    protected function convertObjectToArray(Extension $extension){
        
        $data = array(
            $extension->getPackage() => array(
                'package'   => $extension->getPackage(),
                'version'   => $extension->getVersion(),
                'constraint'=> $extension->getVersionConstraint(),
                'class'     => $extension->getBundleClass(),
                'enabled'   => $extension->isEnabled(),
                'name'      => $extension->getName()
            )
        );
        
        return $data;
        
    }
    
}
    