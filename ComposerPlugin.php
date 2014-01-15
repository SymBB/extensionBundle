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
        foreach($extensionList as $extension => $data){ 
            if($data['enabled'] && !empty($data['package'])){
                $constraint = '';
                if(isset($data['constraint'])){
                    $constraint = $data['constraint'];
                }
                $link = new Link('symbb/symbb', $data['package'],  new VersionConstraint($constraint, $data['version']));
                $required[$data['package']] = $link;
            }
        } 
        
        $package->setRequires($required);
        
    }
    
}