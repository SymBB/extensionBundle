<?php

namespace Seyon\ComposerPlugin;


use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Composer\Package\Package;
use Composer\Package\Link;

class PackageManagerPlugin implements PluginInterface {
    
    public function activate(Composer $composer, IOInterface $io)
    {
        $manager = $composer->getRepositoryManager();

        $requires = array(
            new Link("seyon/teamspeak3-framework", "dev-master")
        );
        
        $package = new Package("seyon/composer-plugin-package", "1.0@dev", "dev-master");
        $package->setRequires($requires);
        
        $repo = $manager->createRepository('package', array());
        $repo->addPackage($package);
        $manager->addRepository($repo);
    }
    
}