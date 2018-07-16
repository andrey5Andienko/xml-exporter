<?php

namespace Test\Unit;

use App\Exporter\Xml\XmlExporter;
use Exception;
use Mockery as m;
use PHPUnit\Framework\TestCase;
use SimpleXMLElement;

class XmlExporterTest extends TestCase
{
    /** @var string */
    protected $json;

    /** @var string */
    protected $content;

    public function setUp()
    {
        parent::setUp();

        $this->json = '{
          "version": "https://jsonfeed.org/version/1",
          "title": "Laravel News Feed",
          "home_page_url": "https://laravel-news.com/",
          "feed_url": "https://laravel-news.com/feed/json",
          "icon": "https://laravel-news.com/apple-touch-icon.png",
          "favicon": "https://laravel-news.com/apple-touch-icon.png",
          "items": "item"
        }';

        $this->content = [
            'version' => 'https://jsonfeed.org/version/1',
            'items' => [
                'item1',
                'item2'
            ]
        ];

    }

    public function tearDown()
    {
        parent::tearDown();

        m::close();

        if (file_exists('xml-for-test.xml')) {
            unlink('xml-for-test.xml');
        }
    }

    /** @test */
    public function it_save_xml_from_json()
    {
        $exporter = new XmlExporter($this->json);
        $exporter->save('xml-for-test');

        $fileContent = file_get_contents('xml-for-test.xml');
        $xmlObject = simplexml_load_string($fileContent);

        $actual = (array)json_decode(json_encode($xmlObject, JSON_UNESCAPED_SLASHES));

        $expected = (array)json_decode($this->json);

        $this->assertSame($expected, $actual);

    }

    /** @test */
    public function it_add_child_in_xml_from_array()
    {
        $expected = "<?xml version=\"1.0\"?>\n" .
            "<news>" .
            "<version>https://jsonfeed.org/version/1</version>" .
            "<items>" .
            "<item>item1</item>" .
            "<item>item2</item>" .
            "</items>" .
            "</news>\n";

        $xml = new SimpleXMLElement('<news></news>');

        $handler = $this->partial(new XmlExporter($this->json));

        $handler->addChildFromArray($this->content, $xml);

        $this->assertSame($expected, $xml->asXML());
    }

    /** @test */
    public function it_will_throw_an_exception_if_json_version_not_supported()
    {
        $jsonVersion = 'https://jsonfeed.org/version/1';

        $handler = $this->partial(new XmlExporter($this->json));

        $this->assertTrue($handler->checkVersion($jsonVersion));

        $jsonVersion = 'https://jsonfeed.org/version/2';

        $this->expectException(Exception::class);

        $this->expectExceptionMessage('Json version must have https://jsonfeed.org/version/1');

        $handler->checkVersion($jsonVersion);
    }

    public function partial($object)
    {
        return m::mock($object)->makePartial()->shouldAllowMockingProtectedMethods();
    }
}