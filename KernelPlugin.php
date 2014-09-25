<?php
/**
*
* @package symBB
* @copyright (c) 2013-2014 Christian Wielath
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/
namespace Symbb\ExtensionBundle;

class KernelPlugin {
    
    public static function addBundles(&$bundles)
    {
        $extensionList  = \Symbb\ExtensionBundle\Api::getExtensions();
            
        foreach($extensionList as $extensionKey => $extension){ 
            if($extension->isEnabled()){
                $class      = $extension->getBundleClass();
                $bundles[]  = new $class();
            }
        } 
        
    }
    
}
