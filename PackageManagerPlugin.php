<?php

namespace Seyon\ComposerPlugin;


use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Plugin\PluginEvents;

class PackageManagerPlugin implements PluginInterface {
    
    public function activate(Composer $composer, IOInterface $io)
    {
        $package = $composer->getPackage();
        $requires = $package->getRequires();
        $requires["seyon/teamspeak3-framework"] = "dev-master";
        $package->setRequires($requires);
    }
    
    public static function getSubscribedEvents()
    {
        return array(
            PluginEvents::COMMAND => array(
                array('onCommandDownload', 0)
            ),
        );
    }
    
}