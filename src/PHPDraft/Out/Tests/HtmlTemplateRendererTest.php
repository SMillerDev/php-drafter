<?php

/**
 * This file contains the TemplateGeneratorTest.php
 *
 * @package PHPDraft\Out
 * @author  Sean Molenaar<sean@seanmolenaar.eu>
 */

namespace PHPDraft\Out\Tests;

use Lunr\Halo\LunrBaseTest;
use PHPDraft\Out\HtmlTemplateRenderer;

/**
 * Class TemplateGeneratorTest
 *
 * @covers \PHPDraft\Out\HtmlTemplateRenderer
 */
class HtmlTemplateRendererTest extends LunrBaseTest
{
    /**
     * @var HtmlTemplateRenderer
     */
    protected HtmlTemplateRenderer $class;

    public static function parsableDataProvider(): array
    {
        $return        = [];
        $expected_base = [
                'COLOR_1' => 'green',
                'COLOR_2' => 'light_green',
        ];


        $return['empty'] = [ ['content' => []], $expected_base ];

        $return['only title'] = [
                [
                        'content' => [
                                [
                                        'meta' => [
                                                'title' => [ 'content' => 'Title' ],
                                        ],
                                        'content' => [],
                                ],

                        ],
                ],
            $expected_base + ['TITLE' => 'Title'],
        ];

        $return['title and metadata'] = [
                [
                        'content' => [
                                [
                                        'meta' => [
                                                'title' => [ 'content' => 'Title' ],
                                        ],
                                        'attributes' => [
                                                'metadata' => [
                                                        'content' => [
                                                                [
                                                                        'content' => [
                                                                                'key' => [ 'content' => 'Some_key' ],
                                                                                'value' => [ 'content' => 'Value' ],
                                                                        ]
                                                                ],
                                                                [
                                                                        'content' => [
                                                                                'key' => [ 'content' => 'Some_key2' ],
                                                                                'value' => [ 'content' => 'Value2' ],
                                                                        ]
                                                                ]
                                                        ]
                                                ]
                                        ],
                                        'content' => [],
                                ],

                        ],
                ],
            $expected_base + ['TITLE' => 'Title', 'Some_key' => 'Value', 'Some_key2' => 'Value2'],
        ];

        $return['title and metadata and description'] = [
                [
                        'content' => [
                                [
                                        'meta' => [
                                                'title' => [ 'content' => 'Title' ],
                                        ],
                                        'attributes' => [
                                                'metadata' => [
                                                        'content' => [
                                                                [
                                                                        'content' => [
                                                                                'key' => [ 'content' => 'Some_key' ],
                                                                                'value' => [ 'content' => 'Value' ],
                                                                        ]
                                                                ],
                                                                [
                                                                        'content' => [
                                                                                'key' => [ 'content' => 'Some_key2' ],
                                                                                'value' => [ 'content' => 'Value2' ],
                                                                        ]
                                                                ]
                                                        ]
                                                ]
                                        ],
                                        'content' => [
                                                [
                                                        'element' => 'copy',
                                                        'content' => 'Some description',
                                                ]
                                        ],
                                ],

                        ],
                ],
            $expected_base + ['TITLE' => 'Title', 'Some_key' => 'Value', 'Some_key2' => 'Value2', 'DESC' => 'Some description'],
        ];

        return $return;
    }

    /**
     * Provide HTTP status codes
     *
     * @return array<int, array<int, int|string>>
     */
    public static function responseStatusProvider(): array
    {
        $return = [];

        $return[] = [ 200, 'text-success' ];
        $return[] = [ 204, 'text-success' ];
        $return[] = [ 304, 'text-warning' ];
        $return[] = [ 404, 'text-error' ];
        $return[] = [ 501, 'text-error' ];

        return $return;
    }

    /**
     * Provide HTTP methods
     *
     * @return array<int, array<int, string>>
     */
    public static function requestMethodProvider(): array
    {
        $return = [];

        $return[] = [ 'POST', 'fas POST fa-plus-square' ];
        $return[] = [ 'post', 'fas POST fa-plus-square' ];
        $return[] = [ 'get', 'fas GET fa-arrow-circle-down' ];
        $return[] = [ 'put', 'fas PUT fa-pen-square' ];
        $return[] = [ 'delete', 'fas DELETE fa-minus-square' ];
        $return[] = [ 'head', 'fas HEAD fa-info' ];
        $return[] = [ 'options', 'fas OPTIONS fa-sliders-h' ];
        $return[] = [ 'PATCH', 'fas PATCH fa-band-aid' ];
        $return[] = [ 'connect', 'fas CONNECT fa-ethernet' ];
        $return[] = [ 'trace', 'fas TRACE fa-route' ];
        $return[] = [ 'cow', 'fas COW' ];

        return $return;
    }

    /**
     * Set up tests
     *
     * @return void
     */
    public function setUp(): void
    {
        $this->class = new HtmlTemplateRenderer('default', 'none');
        $this->baseSetUp($this->class);
    }

    /**
     * Test if the value the class is initialized with is correct
     *
     * @covers \PHPDraft\Out\HtmlTemplateRenderer
     */
    public function testSetupCorrectly(): void
    {
        $this->assertSame('default', $this->get_reflection_property_value('template'));
        $this->assertEquals('none', $this->get_reflection_property_value('image'));
    }

    /**
     * Test if the value the class is initialized with is correct
     *
     * @covers \PHPDraft\Out\HtmlTemplateRenderer::strip_link_spaces
     */
    public function testStripSpaces(): void
    {
        $return = $this->class->strip_link_spaces('hello world');
        $this->assertEquals('hello-world', $return);
    }

    /**
     * Test if the value the class is initialized with is correct
     *
     * @dataProvider responseStatusProvider
     *
     * @param int    $code HTTP code
     * @param string $text Class to return
     *
     * @covers       \PHPDraft\Out\HtmlTemplateRenderer::get_response_status
     */
    public function testResponseStatus(int $code, string $text): void
    {
        $return = HtmlTemplateRenderer::get_response_status($code);
        $this->assertEquals($text, $return);
    }

    /**
     * Test if the value the class is initialized with is correct
     *
     * @dataProvider requestMethodProvider
     *
     * @param string $method HTTP Method
     * @param string $text   Class to return
     *
     * @covers       \PHPDraft\Out\HtmlTemplateRenderer::get_method_icon
     */
    public function testRequestMethod(string $method, string $text): void
    {
        $return = HtmlTemplateRenderer::get_method_icon($method);
        $this->assertEquals($text, $return);
    }

    /**
     * Test if the value the class is initialized with is correct
     *
     * @covers \PHPDraft\Out\HtmlTemplateRenderer::find_include_file
     */
    public function testIncludeFileDefault(): void
    {
        $return = $this->class->find_include_file('default');
        $this->assertEquals('PHPDraft/Out/HTML/default/main.twig', $return);
    }

    /**
     * Test if the value the class is initialized with is correct
     *
     * @covers \PHPDraft\Out\HtmlTemplateRenderer::find_include_file
     */
    public function testIncludeFileFallback(): void
    {
        $return = $this->class->find_include_file('gfsdfdsf');
        $this->assertEquals('PHPDraft/Out/HTML/default/main.twig', $return);
    }

    /**
     * Test if the value the class is initialized with is correct
     *
     * @covers \PHPDraft\Out\HtmlTemplateRenderer::find_include_file
     */
    public function testIncludeFileNone(): void
    {
        $return = $this->class->find_include_file('gfsdfdsf', 'xyz');
        $this->assertEquals(NULL, $return);
    }

    /**
     * Test if the value the class is initialized with is correct
     *
     * @covers \PHPDraft\Out\HtmlTemplateRenderer::find_include_file
     */
    public function testIncludeFileSingle(): void
    {
        set_include_path(TEST_STATICS . '/include_single:' . get_include_path());
        $return = $this->class->find_include_file('hello', 'txt');
        $this->assertEquals('hello.txt', $return);
    }

    /**
     * Test if the value the class is initialized with is correct
     *
     * @covers \PHPDraft\Out\HtmlTemplateRenderer::find_include_file
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

    /**
     * @covers \PHPDraft\Out\HtmlTemplateRenderer::get
     */
    public function testGetTemplateFailsEmpty(): void
    {
        $this->expectException('PHPDraft\Parse\ExecutionException');
        $this->expectExceptionMessage('Couldn\'t find template \'cow\'');
        $this->set_reflection_property_value('template', 'cow');
        $json = '{"content": [{"content": "hello"}]}';

        $this->assertStringEqualsFile(TEST_STATICS . '/empty_html_template', $this->class->get(json_decode($json)));
    }

    /**
     * @covers \PHPDraft\Out\HtmlTemplateRenderer::get
     * @group  twig
     */
    public function testGetTemplate(): void
    {
        $json = '{"content": [{"content": "hello"}]}';

        $this->assertStringEqualsFile(TEST_STATICS . '/empty_html_template', $this->class->get(json_decode($json)));
    }

    /**
     * @covers \PHPDraft\Out\HtmlTemplateRenderer::get
     * @group  twig
     */
    public function testGetTemplateSorting(): void
    {
        $this->set_reflection_property_value('sorting', 3);
        $json = '{"content": [{"content": "hello"}]}';

        $this->assertStringEqualsFile(TEST_STATICS . '/empty_html_template', $this->class->get(json_decode($json)));
    }

    /**
     * @covers \PHPDraft\Out\HtmlTemplateRenderer::get
     * @group  twig
     */
    public function testGetTemplateMetaData(): void
    {
        $this->set_reflection_property_value('sorting', 3);
        $json = <<<'TAG'
{"content": [{"content": [], "attributes": {
"metadata": {"content": [
{"content":{"key": {"content": "key"}, "value": {"content": "value"}}}
]},
"meta": {"title": {"content": "title"}}
}}]}
TAG;

        $this->assertStringEqualsFile(TEST_STATICS . '/basic_html_template', $this->class->get(json_decode($json)));
    }

    /**
     * @covers \PHPDraft\Out\HtmlTemplateRenderer::get
     * @group  twig
     */
    public function testGetTemplateCategories(): void
    {
        $this->set_reflection_property_value('sorting', 3);
        $json = <<<'TAG'
{"content": [
{"content": [{"element": "copy", "content": "__desc__"}, {"element": "category", "content": []}],
 "attributes": {
"metadata": {"content": [
{"content":{"key": {"content": "key"}, "value": {"content": "value"}}}
]},
"meta": {"title": {"content": "title"}}
}}]}
TAG;

        $this->assertStringEqualsFile(TEST_STATICS . '/full_html_template', $this->class->get(json_decode($json)));
    }

    /**
     * @covers       \PHPDraft\Out\BaseTemplateRenderer::parse_base_data
     * @dataProvider parsableDataProvider
     */
    public function testParseBaseData(array $input, array $expected): void
    {
        $method = $this->get_reflection_method('parse_base_data');
        $method->invokeArgs($this->class, [ json_decode(json_encode($input)) ]);

        $this->assertPropertyEquals('base_data', $expected);
    }
}
