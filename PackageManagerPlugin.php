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
        $package->setRequires($requires);
        var_dump($requires);
    }
    
}