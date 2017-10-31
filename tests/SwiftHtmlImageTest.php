<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use S4A\SwiftHtmlImage;

class SwiftHtmlImageTest extends TestCase
{
    protected $swiftMessage;

    /**
     * @var SwiftHtmlImage
     */
    protected $swiftHtmlImage;

    protected $embedCallsCount;

    protected function setUp(){
        $this->swiftMessage = $this->createMock(\Swift_Message::class);
        $this->swiftMessage->method('embed')
            ->will($this->returnCallback(array($this,'embedCallback')));

        $embedCallsCount = 0;
    }

    public function embedCallback(){
        $this->embedCallsCount++;
        return "embed-" . $this->embedCallsCount;
    }

    public function testSimple()
    {
        $this->swiftMessage->expects($this->once())
            ->method('embed');
        $html = '<img src="example.jpg">';

        $swiftHtmlImage = new SwiftHtmlImage(__DIR__ . '/img');

        $this->assertEquals('<img src="embed-1">', $swiftHtmlImage->embedImages($this->swiftMessage, $html));
    }

    public function testWithUpperCaseTags()
    {
        $this->swiftMessage->expects($this->once())
            ->method('embed');
        $html = '<IMG SRC="example.jpg">';

        $swiftHtmlImage = new SwiftHtmlImage(__DIR__ . '/img');

        $this->assertEquals('<IMG SRC="embed-1">', $swiftHtmlImage->embedImages($this->swiftMessage, $html));
    }

    public function testWithMixedCaseTags()
    {
        $this->swiftMessage->expects($this->once())
            ->method('embed');
        $html = '<iMg SrC="example.jpg">';

        $swiftHtmlImage = new SwiftHtmlImage(__DIR__ . '/img');

        $this->assertEquals('<iMg SrC="embed-1">', $swiftHtmlImage->embedImages($this->swiftMessage, $html));
    }

    public function testDifferentWhitespace()
    {
        $this->swiftMessage->expects($this->once())
            ->method('embed');
        $html = '<img 	src	 	=     "example.jpg">';

        $swiftHtmlImage = new SwiftHtmlImage(__DIR__ . '/img');

        $this->assertEquals('<img 	src	 	=     "embed-1">', $swiftHtmlImage->embedImages($this->swiftMessage, $html));
    }

    public function testMultipleAttributes()
    {
        $this->swiftMessage->expects($this->once())
            ->method('embed');
        $html = '<img class="image" src="example.jpg" style="border: 1px solid black">';

        $swiftHtmlImage = new SwiftHtmlImage(__DIR__ . '/img');

        $this->assertEquals('<img class="image" src="embed-1" style="border: 1px solid black">', $swiftHtmlImage->embedImages($this->swiftMessage, $html));
    }
    
    public function testEqualImagesSameEmbedCode(){
        $this->swiftMessage->expects($this->once())
            ->method('embed');
        $html = '<img src="example.jpg"><img src="example.jpg">';

        $swiftHtmlImage = new SwiftHtmlImage(__DIR__ . '/img');

        $this->assertEquals('<img src="embed-1"><img src="embed-1">', $swiftHtmlImage->embedImages($this->swiftMessage, $html));
    }

    public function testNotEqualImagesNotSameEmbedCode(){
        $this->swiftMessage->expects($this->exactly(2))
            ->method('embed');

        $html = '<img src="example.jpg"><img src="example2.jpg">';

        $swiftHtmlImage = new SwiftHtmlImage(__DIR__ . '/img');

        $this->assertEquals('<img src="embed-1"><img src="embed-2">', $swiftHtmlImage->embedImages($this->swiftMessage, $html));
    }

    public function testEqualAndNotEqualImagesTwoEmbedCodes(){
        $this->swiftMessage->expects($this->exactly(2))
            ->method('embed');

        $html = '<img src="example.jpg"><img src="example2.jpg"><img src="example.jpg">';

        $swiftHtmlImage = new SwiftHtmlImage(__DIR__ . '/img');

        $this->assertEquals('<img src="embed-1"><img src="embed-2"><img src="embed-1">', $swiftHtmlImage->embedImages($this->swiftMessage, $html));
    }
}