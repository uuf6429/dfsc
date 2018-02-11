<?php

namespace uuf6429\DFSC\Config;

use League\JsonGuard\ValidationError;
use League\JsonGuard\Validator;
use Symfony\Component\Yaml\Yaml;
use uuf6429\DFSC\Exception\BrokenSchemaException;

/**
 * @todo
 */
class Config
{
    private static $SCHEMA_LOCATION = __DIR__ . '/../../schema/schema.json';

    /**
     * @var string
     */
    private $configFile;

    /**
     * @var mixed
     */
    private $config;

    /**
     * @var string[]
     */
    private $errors;

    /**
     * @var bool
     */
    private $configLoaded;

    /**
     * @param string $configFile
     */
    public function __construct($configFile)
    {
        $this->configFile = $configFile;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        $this->load();

        return empty($this->getErrors());
    }

    /**
     * @return string[]
     */
    public function getErrors()
    {
        $this->load();

        return $this->errors;
    }

    private function load()
    {
        if ($this->configLoaded) {
            return;
        }

        try {
            $this->config = Yaml::parseFile($this->configFile, Yaml::PARSE_OBJECT_FOR_MAP);
            $validator = new Validator($this->config, $this->getSchema());
            $this->errors = array_map(
                function (ValidationError $error) {
                    return $error->getMessage();
                },
                $validator->errors()
            );
            // TODO apply parameter substitution and log any errors
        } catch (\Exception $ex) {
            $this->errors = [$ex->getMessage()];
        }

        $this->configLoaded = true;
    }

    /**
     * @return mixed
     *
     * @throws BrokenSchemaException
     */
    private function getSchema()
    {
        $result = json_decode(file_get_contents(self::$SCHEMA_LOCATION));

        if ($result === null) {
            throw new BrokenSchemaException(
                'Schema is broken and cannot be loaded: ' . json_last_error_msg()
            );
        }

        return $result;
    }
}
