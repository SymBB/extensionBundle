<?php

namespace SymBB\ExtensionBundle;


use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Symfony\Component\Yaml\Parser;
use Composer\Package\Link;
use Composer\Package\LinkConstraint\VersionConstraint;

class ComposerPlugin implements PluginInterface {
    
    public function activate(Composer $composer, IOInterface $io)
    {
        
        $extensionList  = \SymBB\ExtensionBundle\KernelPlugin::getExtensions();

        $package        = $composer->getPackage();
        
        $required   = $package->getRequires();
        foreach($extensionList['extensions'] as $extension => $data){ 
            if($data['enabled']){
                $link = new Link('symbb/symbb', $data['package'],  new VersionConstraint('', $data['version']));
                $required[$data['package']] = $link;
            }
        } 
        
        $package->setRequires($required);
        
    }
    
}