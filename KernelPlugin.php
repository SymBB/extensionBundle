<?php

namespace SymBB\ExtensionBundle;

use Symfony\Component\Yaml\Parser;

class KernelPlugin {
    
     const CONFIG_FILE = '/../../../../../app/config/extensions.yml';


    public static function addBundles($bundles)
    {
        $extensionList  = self::getExtensions();
            
        foreach($extensionList as $extension => $data){ 
            if($data['enabled']){
                $class      = $data['class'];
                $bundles[]  = new $class;
            }
        } 
        
    }
    
    public static function getExtensions(){
        $searchPath     = __DIR__.self::CONFIG_FILE;
        $extensionList  = array();
        if(\is_file($searchPath)){
            $yaml           = new Parser();
            $extensionList  = $yaml->parse(file_get_contents($searchPath));
            $extensionList  = $extensionList['extensions'];
        }
        return $extensionList;
    }
    
}