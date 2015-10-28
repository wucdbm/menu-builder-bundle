<?php

namespace Wucdbm\Bundle\MenuBuilderBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Routing\Router;

class ImportRoutesCommand extends ContainerAwareCommand {

    protected function configure() {
        $this
            ->setName('wucdbm_menu_builder:import_routes')
            ->setDescription('Import routes from your application')
            ->addOption('keep', 'k', InputOption::VALUE_NONE, 'Keep routes that were not found?');
    }


    protected function execute(InputInterface $input, OutputInterface $output) {
        $container = $this->getContainer();


        /** @var $router Router */
        $router = $container->get('router');

        $manager = $container->get('wucdbm_menu_builder.manager.routes');

        $manager->importRouter($router);
    }

}