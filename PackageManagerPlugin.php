<?php

namespace Seyon\ComposerPlugin;


use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class PackageManagerPlugin implements PluginInterface {
    
    public function activate(Composer $composer, IOInterface $io)
    {
        $installer = $composer->getInstallationManager()->getInstaller('seyon/symbb');
        var_dump(get_class($installer));
    }
    
}