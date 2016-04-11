<?php

/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Glugox\Integration\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GreetingCommand
 */
class ImportCommand extends Command {

    /**
     * Code argument
     */
    const CODE_ARGUMENT = 'code';

    /**
     * Force option
     */
    const FORCE = 'force';

    /**
     * Strict mode
     */
    const STRICT_MODE = 'strict-mode';

    /**
     * Default mode
     */
    const DEFAULT_MODE = 'default-mode';

    /**
     * Import all code
     */
    const IMPORT_ALL_CODE = 'all';

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_registry = null;

    /**
     * Manager
     */
    private $_manager;


    /**
     * @param \Glugox\Integration\Event\Manager $manager
     */
    public function __construct(\Glugox\Integration\Event\Manager $manager, \Magento\Framework\Registry $registry)
    {
        $this->_manager = $manager;
        $this->_registry = $registry;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure() {
        $this->setName('glugox:integration:import')
                ->setDescription('Imports data from external source.')
                ->setDefinition([
                    new InputArgument(
                            self::CODE_ARGUMENT, InputArgument::OPTIONAL, 'Code'
                    ),
                    new InputOption(
                            self::FORCE, '-f', InputOption::VALUE_NONE, 'Proceed on error(s)'
                    ),
        ]);
        parent::configure();
    }


    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $code = $input->getArgument(self::CODE_ARGUMENT);
        $force = $input->getOption(self::FORCE);
        $mode = self::DEFAULT_MODE;

        if (is_null($code)) {
            $code = self::IMPORT_ALL_CODE;
        }
        if ($force) {
            $mode = self::STRICT_MODE;
        }
        $output->writeln('<info>Starting import: ' . $code . ' in ' . $mode . '...</info>');
        $this->_registry->register(\Glugox\Integration\Event\Manager::CURRENT_CMD_OUTPUT_INTERFACE, $output);
        $this->_manager->run();
    }


}
