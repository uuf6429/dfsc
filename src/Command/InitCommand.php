<?php

namespace uuf6429\DFSC\Command;

use Symfony\Component\Console\Command\Command;
use uuf6429\DFSC\Config\ConfigAware;
use uuf6429\DFSC\Config\ConfigAwareTrait;

/**
 * @todo
 */
class InitCommand extends Command implements ConfigAware
{
    use ConfigAwareTrait;

    /**
     * @inheritdoc
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('init');
    }
}
