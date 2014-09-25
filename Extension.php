<?php
/**
*
* @package symBB
* @copyright (c) 2013-2014 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace Symbb\ExtensionBundle;

class Extension {
    
     protected $package = '';
     protected $name = '';
     protected $version = '';
     protected $versionConstraint = '';
     protected $bundleClass = '';
     protected $enabled = true;
     protected $composer = true;

     public function getPackage(){
         return $this->package;
     }
     
     public function getVersion(){
         return $this->version;
     }
     
     public function getVersionConstraint(){
         return $this->versionConstraint;
     }
     
     public function getName(){
         return $this->name;
     }
     
     public function getBundleClass(){
         return $this->bundleClass;
     }
     
     public function getDir(){
         $reflector = new \ReflectionClass($this->bundleClass);
         return dirname($reflector->getFileName());
     }
     
     public function isEnabled(){
         return $this->enabled;
     }
     
     public function hasRouting(){
         
         $dir   = $this->getDir();
         $file  = $dir .'/Resources/config/routing.yml';
         
         if(\is_file($file)){
             return true;
         }
         
         return false;
     }
     
     public function getPackageForUrl(){
         $package = $this->getPackage();
         $package = \str_replace('/', '|', $package);
         return $package;
     }
     
     
     
     public function disableComposer(){
         $this->composer = false;
     }
     
     public function hasComposer(){
         return $this->composer;
     }
     
     public function setPackage($value){
         $this->package = $value;
     }
     
     public function setVersion($value){
         $this->version = $value;
     }
     
     public function setVersionConstraint($value){
         $this->versionConstraint = $value;
     }
     
     public function setName($value){
         $this->name = $value;
     }
     
     public function setBundleClass($value){
         $this->bundleClass = $value;
     }
     
     public function enable(){
         $this->enabled = true;
     }
     
     public function disable(){
         $this->enabled = false;
     }

}
