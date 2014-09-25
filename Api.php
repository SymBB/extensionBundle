<?php
/**
*
* @package Symbb
* @copyright (c) 2013-2014 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace Symbb\ExtensionBundle;

use \Symbb\ExtensionBundle\Exception\PackagistNotFound;
use \Symbb\ExtensionBundle\KernelPlugin;
use \Symbb\ExtensionBundle\Extension;
use \Symfony\Component\Yaml\Parser;
use \Symfony\Component\Yaml\Dumper;

class Api {
    
    const EXTENSIONS_FILE = '/../../../../../app/config/extensions.yml';
     
    protected $packagistClient;
    protected $extensions       = array();

    public function __construct()
    {
       $this->packagistClient   = new \Packagist\Api\Client();
       $this->extensions        = self::getExtensions();
    }
    
    public static function getExtensionFilePath(){
        return __DIR__.self::EXTENSIONS_FILE;
    }
    
    public function checkFileAccess(){
        $file = self::getExtensionFilePath();
        return is_writable($file) ;
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
            $class       = '\\'.$nameSpace.'\\'.\str_replace('\\', '' , $nameSpace);
            
            $extension = new Extension();
            $extension->setPackage($packageName);
            $extension->setName($repoData['full_name']);
            $extension->setVersion($version);
            $extension->setVersionConstraint($versionConstraint);
            $extension->setBundleClass($class);
            $extension->disable();
            
            $this->addExtension($extension);
            
        } else {
            throw new PackagistNotFound('Extension Package not found in Packagist!');
        }
        
    }
    
    /**
     * 
     * @return \Symbb\ExtensionBundle\Extension
     */
    public static function getExtensions(){
        $searchPath     = self::getExtensionFilePath();
        $extensionListFinal  = array();

        if(\is_file($searchPath)){
            $yaml           = new Parser();
            $extensionList  = $yaml->parse(file_get_contents($searchPath));

            if(!empty($extensionList)){
                foreach($extensionList as $key => $extensionData){
                    if(!empty($extensionData)){
                        $extensionListFinal[$key] = self::getExtensionFromData($extensionData);
                    }
                }
            }
        }

        return $extensionListFinal;
    }
    
    public function disable($extensionName){
        $extensions = self::getExtensions();
        $extensions[$extensionName]->disable();
        
        $finalData = array();
        
        foreach($extensions as $key => $object){
            $finalData[$key] = $this->convertObjectToArray($object);
        }
        
        $this->createFile($finalData);
    }
    
    public function enable($extensionName){
        $extensions = self::getExtensions();
        $extensions[$extensionName]->enable();
        
        $finalData = array();
        
        foreach($extensions as $key => $object){
            $finalData[$key] = $this->convertObjectToArray($object);
        }
        
        $this->createFile($finalData);
    }
    
    public function remove($extensionName){
        $extensions = self::getExtensions();
        unset($extensions[$extensionName]);
        
        $finalData = array();
        
        foreach($extensions as $key => $object){
            $finalData[$key] = $this->convertObjectToArray($object);
        }
        
        $this->createFile($finalData);
    }
    
    public function clearCache(){
        $dir = __DIR__;
        $dir = \explode('vendor', $dir);
        $dir = reset($dir);

        \exec('php '.$dir.'app/console cache:clear --env=dev');
        \exec('php '.$dir.'app/console cache:clear --env=prod');
    }
    
    public function addExtension(Extension $extension){
        $extensions = self::getExtensions();
        $extensions[$extension->getPackage()] = $extension;
        
        $finalData = array();
        
        foreach($extensions as $key => $object){
            $finalData[$key] = $this->convertObjectToArray($object);
        }
        
        $this->createFile($finalData);
    }
    
    protected function createFile($extensions){
        $dumper = new Dumper();
        $yml = $dumper->dump($extensions);
        \file_put_contents(self::getExtensionFilePath(), $yml);
    }

    protected function convertObjectToArray(Extension $extension){
        
        $data = array(
            'package'       => $extension->getPackage(),
            'version'       => $extension->getVersion(),
            'constraint'    => $extension->getVersionConstraint(),
            'bundleClass'   => $extension->getBundleClass(),
            'enabled'       => $extension->isEnabled(),
            'name'          => $extension->getName(),
            'composer'      => $extension->hasComposer()
        );
        
        return $data;
        
    }
    
    /**
     * 
     * @param type $data
     * @return \Symbb\ExtensionBundle\Extension
     */
    public static function getExtensionFromData($data){
        
        if(!isset($data['versionConstraint'])){
            $data['versionConstraint'] = '';
        }
     
        $extension = new Extension();
        $extension->setPackage($data['package']);
        $extension->setName($data['name']);
        $extension->setVersion($data['version']);
        $extension->setVersionConstraint($data['versionConstraint']);
        $extension->setBundleClass($data['bundleClass']);
        if($data['enabled']){
            $extension->enable();
        } else {
           $extension->disable(); 
        }
        if(!$data['composer']){
            $extension->disableComposer();
        }
        
        return $extension;
    }
    
}
    
