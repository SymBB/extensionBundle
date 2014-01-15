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
        
        $searchPath = __DIR__.'/../../../../../app/config/';
        
        $yaml           = new Parser();
        $extensionList  = $yaml->parse(file_get_contents($searchPath.'extensions.yml'));

        
        $package        = $composer->getPackage();
   
        
        //$package    = new Package('symbb/composer-plugin-package', 'dev-master', 'dev-master');
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