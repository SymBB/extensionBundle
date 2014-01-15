<?php

namespace Seyon\ComposerPlugin;


use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;

class PackageManagerPlugin implements PluginInterface {
    
    public function activate(Composer $composer, IOInterface $io)
    {
        $manager = $composer->getRepositoryManager();
        $config = array(
            'package' => array(
                'name' => 'seyon/composer-plugin-package',
                'version' => "dev-master",
                "require"=> array(
                    "seyon/teamspeak3-framework" => "dev-master"
               )
            )
        );
        $repo = $manager->createRepository('package', $config);
        $manager->addRepository($repo);
    }
    
}