<?php
/**
*
* @package symBB
* @copyright (c) 2013-2014 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace SymBB\ExtensionBundle;

class Extension {
    
     protected $package = '';
     protected $name = '';
     protected $version = '';
     protected $versionConstraint = '';
     protected $bundleClass = '';
     protected $dir = '';
     protected $enabled = true;
     
     
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
         return $this->dir;
     }
     
     public function isEnabled(){
         return $this->enabled;
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
     
     public function setDir($value){
         $this->dir = $value;
     }
     
     public function enabled(){
         $this->enabled = true;
     }
     
     public function disabled(){
         $this->enabled = false;
     }

}