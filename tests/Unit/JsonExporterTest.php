<?php

namespace Test\Unit;

use App\Exporter\Json\JsonExporter;
use Mockery as m;
use PHPUnit\Framework\TestCase;

class JsonExporterTest extends TestCase
{
    /** @var string */
    protected $xml;

    public function setUp()
    {
        parent::setUp();
        $this->xml = '<?xml version="1.0"?>
            <news>
                <version>https://jsonfeed.org/version/1</version>
                <title>Laravel News Feed</title>
                <items>
                    <item>
                        <id>1796</id>
                        <title>Laravel Event Projector Released</title>
                        <url>https://laravel-news.com/laravel-event-projector</url>
                        <date_published>2018-07-13T13:15:58+00:00</date_published>
                        <date_modified>2018-07-13T12:31:07+00:00</date_modified>
                        <author>
                            <name>Paul Redmond</name>
                        </author>
                    </item>
                </items>
            </news>';
    }

    /** @test */
    public function it_save_xml_from_json()
    {
        $jsonExporter = new JsonExporter($this->xml);
        $jsonExporter->save('json-for-test');

        $fileContent = file_get_contents('json-for-test.json');
        $actual = json_decode($fileContent, true);

        $xmlObject = simplexml_load_string($this->xml);
        $expected = json_decode(json_encode($xmlObject), true);

        $this->assertSame($expected, $actual);
    }

    public function partial($object)
    {
        return m::mock($object)->makePartial()->shouldAllowMockingProtectedMethods();
    }

    public function tearDown()
    {
        parent::tearDown();

        if (file_exists('json-for-test.json')) {
            unlink('json-for-test.json');
        }

        m::close();
    }
}