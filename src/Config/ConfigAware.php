<?php

namespace uuf6429\DFSC\Config;

interface ConfigAware
{
    /**
     * @param Config $config
     */
    public function setConfig(Config $config);

    /**
     * @return Config
     */
    public function getConfig();
}
