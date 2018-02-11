<?php

namespace uuf6429\DFSC;

use PHPUnit\Framework\TestCase;
use Vfs\FileSystem;

class SchemaTest extends TestCase
{
    /**
     * @var FileSystem
     */
    private static $fs;

    /**
     * @inheritdoc
     */
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        self::$fs = FileSystem::factory('vfs://');
        self::$fs->mount();
    }

    /**
     * @inheritdoc
     */
    public static function tearDownAfterClass()
    {
        self::$fs->unmount();

        parent::tearDownAfterClass();
    }

    /**
     * @param string|string[] $data
     * @param bool $expectedSuccess
     * @param string[] $expectedErrors
     *
     * @dataProvider schemaScenarioDataProvider
     */
    public function testSchemaScenario($data, $expectedSuccess, $expectedErrors)
    {
        $path = 'vfs://' . uniqid('data_', true) . '.yml';
        file_put_contents($path, implode(PHP_EOL, (array) $data));

        try {
            $config = new Config\Config($path);
            $this->assertEquals(
                [
                    'success' => $expectedSuccess,
                    'errors' => $expectedErrors,
                ],
                [
                    'success' => $config->isValid(),
                    'errors' => $config->getErrors(),
                ]
            );
        } finally {
            @unlink($path);
        }
    }

    /**
     * @return array
     */
    public function schemaScenarioDataProvider()
    {
        return [
            'empty config' => [
                '$data' => '',
                '$expectedSuccess' => false,
                '$expectedErrors' => [
                    'The data must be a(n) object.',
                ]
            ],
            'empty object config' => [
                '$data' => '{}',
                '$expectedSuccess' => false,
                '$expectedErrors' => [
                    'The object must contain the properties ["containers"].',
                ]
            ],
            'invalid content config' => [
                '$data' => 'fsd%#&*%(&)d',
                '$expectedSuccess' => false,
                '$expectedErrors' => [
                    'The data must be a(n) object.',
                ]
            ],
            'invalid persistence type' => [
                '$data' => [
                    'persistence: invalid',
                    'containers: {}',
                ],
                '$expectedSuccess' => false,
                '$expectedErrors' => [
                    'The string must match the pattern ^ignore|remove-volumes|data-container.'
                ]
            ],
            'invalid root property' => [
                '$data' => [
                    'invalid: true',
                    'containers: {}',
                ],
                '$expectedSuccess' => false,
                '$expectedErrors' => [
                    'The object must not contain additional properties (["invalid"]).'
                ]
            ],
            'required entries config' => [
                '$data' => [
                    'containers:',
                    '  MyContainer:',
                    '    image: my/image:dev',
                ],
                '$expectedSuccess' => true,
                '$expectedErrors' => []
            ],
            'example.yml config' => [
                '$data' => file_get_contents(__DIR__ . '/../schema/example.yml'),
                '$expectedSuccess' => true,
                '$expectedErrors' => []
            ],
        ];
    }
}
