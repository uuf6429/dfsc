<?php

namespace uuf6429\DFSC\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use uuf6429\DFSC\Config\ConfigAware;
use uuf6429\DFSC\Config\ConfigAwareTrait;
use uuf6429\DFSC\Exception\InvalidConfigurationException;

/**
 * @todo
 */
class VerifyCommand extends Command implements ConfigAware
{
    use ConfigAwareTrait;

    /**
     * @inheritdoc
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('verify');
    }

    /**
     * @inheritdoc
     *
     * @throws InvalidConfigurationException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->config->isValid()) {
            throw new InvalidConfigurationException($this->config->getErrors());
        }
    }
}
