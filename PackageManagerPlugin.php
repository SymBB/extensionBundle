<?php

namespace Seyon\ComposerPlugin;


use Composer\Composer;
use Composer\IO\IOInterface;
use Composer\Plugin\PluginInterface;
use Symfony\Component\Yaml\Parser;

class PackageManagerPlugin implements PluginInterface {
    
    public function activate(Composer $composer, IOInterface $io)
    {
        
        $searchPath = __DIR__.'/../../../../../app/config/';
        
        $yaml   = new Parser();
        $extensionList = $yaml->parse(file_get_contents($searchPath.'extensions.yml'));

        $required = array();
        
        foreach($extensionList['extensions'] as $extension => $data){
            if($data['enabled']){
                $required[$data['package']] = $data['version'];
            }
        }
        
        $manager = $composer->getRepositoryManager();
        $config = array(
            'package' => array(
                'name' => 'symbb/composer-plugin-package',
                'version' => "dev-master",
                "require"=> $required
            )
        );
        $repo = $manager->createRepository('package', $config);
        $manager->addRepository($repo);
    }
    
}