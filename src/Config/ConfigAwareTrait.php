<?php

namespace uuf6429\DFSC\Config;

trait ConfigAwareTrait
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @param Config $config
     */
    public function setConfig(Config $config)
    {
        $this->config = $config;
    }

    /**
     * @return Config
     */
    public function getConfig()
    {
        return $this->config;
    }
}
