<?php

namespace Seyon\ComposerPlugin;


use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class PackageManagerPlugin implements PluginInterface {
    
    public function activate(Composer $composer, IOInterface $io)
    {
        $package = $composer->getPackage();
        $requires = $package->getRequires();
        $requires["seyon/teamspeak3-framework"] = "dev-master";
        $package->setRequires($requires);
    }
    
}