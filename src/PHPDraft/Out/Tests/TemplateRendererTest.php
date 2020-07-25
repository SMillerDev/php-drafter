<?php

/**
 * This file contains the TemplateGeneratorTest.php
 *
 * @package PHPDraft\Out
 * @author  Sean Molenaar<sean@seanmolenaar.eu>
 */

namespace PHPDraft\Out\Tests;

use Lunr\Halo\LunrBaseTest;
use PHPDraft\Out\TemplateRenderer;

/**
 * Class TemplateGeneratorTest
 *
 * @covers \PHPDraft\Out\TemplateRenderer
 */
class TemplateRendererTest extends LunrBaseTest
{
    /**
     * @var TemplateRenderer
     */
    protected $class;

    /**
     * Set up tests
     * @return void
     */
    public function setUp(): void
    {
        $this->class      = new TemplateRenderer('default', 'none');
        $this->reflection = new \ReflectionClass('PHPDraft\Out\TemplateRenderer');
    }

    /**
     * Test if the value the class is initialized with is correct
     */
    public function testSetupCorrectly(): void
    {
        $this->assertSame('default', $this->get_reflection_property_value('template'));
        $this->assertEquals('none', $this->get_reflection_property_value('image'));
    }

    /**
     * Test if the value the class is initialized with is correct
     */
    public function testStripSpaces(): void
    {
        $return = $this->class->strip_link_spaces('hello world');
        $this->assertEquals('hello-world', $return);
    }

    /**
     * Provide HTTP status codes
     */
    public function responseStatusProvider(): array
    {
        $return = [];

        $return[] = [200, 'text-success'];
        $return[] = [204, 'text-success'];
        $return[] = [304, 'text-warning'];
        $return[] = [404, 'text-error'];
        $return[] = [501, 'text-error'];

        return $return;
    }

    /**
     * Test if the value the class is initialized with is correct
     *
     * @dataProvider responseStatusProvider
     *
     * @param int    $code HTTP code
     * @param string $text Class to return
     */
    public function testResponseStatus($code, $text): void
    {
        $return = $this->class->get_response_status($code);
        $this->assertEquals($text, $return);
    }

    /**
     * Provide HTTP methods
     */
    public function requestMethodProvider(): array
    {
        $return = [];

        $return[] = ['POST', 'fas POST fa-plus-square'];
        $return[] = ['post', 'fas POST fa-plus-square'];
        $return[] = ['get', 'fas GET fa-arrow-circle-down'];
        $return[] = ['put', 'fas PUT fa-pen-square'];
        $return[] = ['delete', 'fas DELETE fa-minus-square'];
        $return[] = ['head', 'fas HEAD fa-info'];
        $return[] = ['options', 'fas OPTIONS fa-sliders-h'];
        $return[] = ['PATCH', 'fas PATCH fa-band-aid'];
        $return[] = ['connect', 'fas CONNECT fa-ethernet'];
        $return[] = ['trace', 'fas TRACE fa-route'];
        $return[] = ['cow', 'fas COW'];

        return $return;
    }

    /**
     * Test if the value the class is initialized with is correct
     *
     * @dataProvider requestMethodProvider
     *
     * @param int    $code HTTP Method
     * @param string $text Class to return
     */
    public function testRequestMethod($code, $text): void
    {
        $return = $this->class->get_method_icon($code);
        $this->assertEquals($text, $return);
    }

    /**
     * Test if the value the class is initialized with is correct
     */
    public function testIncludeFileDefault(): void
    {
        $return = $this->class->find_include_file('default');
        $this->assertEquals('PHPDraft/Out/HTML/default/main.twig', $return);
    }

    /**
     * Test if the value the class is initialized with is correct
     */
    public function testIncludeFileFallback(): void
    {
        $return = $this->class->find_include_file('gfsdfdsf');
        $this->assertEquals('PHPDraft/Out/HTML/default/main.twig', $return);
    }

    /**
     * Test if the value the class is initialized with is correct
     */
    public function testIncludeFileNone(): void
    {
        $return = $this->class->find_include_file('gfsdfdsf', 'xyz');
        $this->assertEquals(null, $return);
    }

    /**
     * Test if the value the class is initialized with is correct
     */
    public function testIncludeFileSingle(): void
    {
        set_include_path(TEST_STATICS . '/include_single:' . get_include_path());
        $return = $this->class->find_include_file('hello', 'txt');
        $this->assertEquals('hello.txt', $return);
    }

    /**
     * Test if the value the class is initialized with is correct
     */
    public function testIncludeFileMultiple(): void
    {
        set_include_path(TEST_STATICS . '/include_folders:' . get_include_path());
        $return = $this->class->find_include_file('hello', 'txt');
        $this->assertEquals('hello/hello.txt', $return);

        $return = $this->class->find_include_file('test', 'txt');
        $this->assertEquals('templates/test.txt', $return);

        $return = $this->class->find_include_file('text', 'txt');
        $this->assertEquals('templates/text/text.txt', $return);
    }
}