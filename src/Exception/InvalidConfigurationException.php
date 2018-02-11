<?php

namespace uuf6429\DFSC\Exception;

class InvalidConfigurationException extends \RuntimeException
{
    /**
     * @param string[] $reasons
     * @param \Throwable|null $previous
     */
    public function __construct(array $reasons, \Throwable $previous = null)
    {
        $message = sprintf(
            'Configuration is not valid:%s',
            PHP_EOL . implode(PHP_EOL, $reasons)
        );

        parent::__construct($message, 0, $previous);
    }
}
