<?php
/**
 * This file contains the APIBlueprintElementTest.php
 *
 * @package php-drafter\SOMETHING
 * @author  Sean Molenaar<sean@seanmolenaar.eu>
 */

namespace PHPDraft\Model\Tests;


use PHPDraft\Model\DataStructureElement;
use PHPUnit_Framework_TestCase;
use ReflectionClass;

class DataStructureElementTest extends PHPUnit_Framework_TestCase
{
    /**
     * Test Class
     * @var DataStructureElement
     */
    protected $class;

    /**
     * Test reflection
     * @var ReflectionClass
     */
    protected $reflection;

    public function setUp()
    {
        $this->class      = new DataStructureElement();
        $this->reflection = new ReflectionClass('PHPDraft\Model\DataStructureElement');
    }

    public function tearDown()
    {
        unset($this->class);
        unset($this->reflection);
    }

    /**
     * Parse different objects
     *
     * @dataProvider parseObjectProvider
     *
     * @param string               $object   JSON Object
     * @param DataStructureElement $expected Expected Object output
     */
    public function testSuccesfulParse($object, $expected)
    {
        $dep = [];
        $this->class->parse(json_decode($object), $dep);
        $this->assertSame($this->class->key, $expected->key);
        $this->assertSame($this->class->value, $expected->value);
        $this->assertSame($this->class->element, $expected->element);
        $this->assertSame($this->class->type, $expected->type);
    }

    /**
     * Parse different objects and check if the dependencies are saved correctly
     *
     * @dataProvider parseObjectDepProvider
     *
     * @param string $object   JSON Object
     * @param array  $expected Array of expected dependencies
     */
    public function testSuccesfulDependencyCheck($object, $expected)
    {
        $dep = [];
        $this->class->parse(json_decode($object), $dep);
        $this->assertSame($dep, $expected);
    }

    public function parseObjectProvider()
    {
        $return         = [];
        $base1          = new DataStructureElement();
        $base1->key     = 'Content-Type';
        $base1->value   = 'application/json';
        $base1->element = 'member';
        $base1->type    = 'Struct2';

        $base2          = new DataStructureElement();
        $base2->key     = 'Auth2';
        $base2->value   = 'something';
        $base2->element = 'member';
        $base2->type    = 'Struct1';

        $return[] = [
            '{"element": "member","content": {"key": {"element": "string","content": "Content-Type"},' .
            '"value": {"element": "Struct2","content": "application/json"}}}',
            $base1,
        ];
        $return[] = [
            '{"element": "member","content": {"key": {"element": "string","content": "Auth2"},' .
            '"value": {"element": "Struct1","content": "something"}}}',
            $base2,
        ];

        return $return;
    }

    public function parseObjectDepProvider()
    {
        $return   = [];
        $return[] = [
            '{"element": "member","content": {"key": {"element": "string","content": "Content-Type"}' .
            ',"value": {"element": "Struct2","content": "application/json"}}}',
            ['Struct2'],
        ];
        $return[] = [
            '{"element": "member","content": {"key": {"element": "string","content": "Auth2"}' .
            ',"value": {"element": "Struct1","content": "something"}}}',
            ['Struct1'],
        ];
        $return[] = [
            '{"element": "member",
                "meta": {"description": "Update Data Object"},
                "content": {
                    "key": {"element": "string","content": "data"},
                    "value": { "element": "object",
                        "content": [
                            {   "element": "member",
                                "meta": {"description": "Data that needs to be added to the Struct"},
                                "content": {"key": {"element": "string","content": "add"},
                                    "value": {"element": "Struct1"}
                                }
                            },
                            {   "element": "member",
                                "meta": {"description": "Data that needs to be updated in the Struct"},
                                "content": {"key": {"element": "string","content": "update"},
                                    "value": {"element": "Struct2"}
                                }
                            },
                            {   "element": "member",
                                "meta": {"description": "Data that needs to be deleted from the Struct"},
                                "content": {"key": {"element": "string","content": "delete"},
                                    "value": {"element": "object"}
                                }
                            }
                        ]
                    }
                }
            }', ['Struct1', 'Struct2'],
        ];

        return $return;
    }
}
