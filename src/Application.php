<?php

namespace uuf6429\DFSC;

use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use uuf6429\DFSC\Config\ConfigAware;
use uuf6429\DFSC\Config\Config;

class Application extends SymfonyConsoleApplication
{
    private static $OPT_WORK_DIR_L = '--work-dir';
    private static $OPT_WORK_DIR_S = '-w';
    private static $OPT_CONFIG_FILE_L = '--config-file';
    private static $OPT_CONFIG_FILE_S = '-c';

    /**
     * @var Config
     */
    private $config;

    /**
     * @inheritdoc
     *
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    protected function getDefaultCommands()
    {
        return array_merge(
            parent::getDefaultCommands(),
            [
                new Command\InitCommand(),
                new Command\BuildCommand(),
                new Command\VerifyCommand(),
            ]
        );
    }

    /**
     * @inheritdoc
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\Console\Exception\LogicException
     */
    protected function getDefaultInputDefinition()
    {
        $definition = parent::getDefaultInputDefinition();

        $definition->addOptions(
            [
                new InputOption(
                    self::$OPT_WORK_DIR_L,
                    self::$OPT_WORK_DIR_S,
                    InputOption::VALUE_REQUIRED,
                    'Set working directory of project'
                ),
                new InputOption(
                    self::$OPT_CONFIG_FILE_L,
                    self::$OPT_CONFIG_FILE_S,
                    InputOption::VALUE_REQUIRED,
                    'Change default name of config file'
                ),
            ]
        );

        return $definition;
    }

    /**
     * @inheritdoc
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $changeCwd = $input->hasParameterOption([self::$OPT_WORK_DIR_L, self::$OPT_WORK_DIR_S]);
        $oldCwd = getcwd();
        $newCwd = $input->getParameterOption([self::$OPT_WORK_DIR_L, self::$OPT_WORK_DIR_S]);

        try {
            if ($changeCwd) {
                chdir($newCwd);
            }

            return parent::doRun($input, $output);
        } finally {
            if ($changeCwd) {
                chdir($oldCwd);
            }
        }
    }

    /**
     * @inheritdoc
     */
    protected function doRunCommand(SymfonyCommand $command, InputInterface $input, OutputInterface $output)
    {
        if ($command instanceof ConfigAware) {
            $command->setConfig($this->config);
        }

        return parent::doRunCommand($command, $input, $output);
    }
}
