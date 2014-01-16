<?php
/**
*
* @package symBB
* @copyright (c) 2013-2014 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace SymBB\ExtensionBundle;

class KernelPlugin {
    
    public static function addBundles(&$bundles)
    {
        $extensionList  = \SymBB\ExtensionBundle\Api::getExtensions();
            
        foreach($extensionList as $extension => $data){ 
            if($data['enabled']){
                $class      = $data['class'];
                $bundles[]  = new $class();
            }
        } 
        
    }
    
}