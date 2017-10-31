<?php

namespace S4A;


class SwiftHtmlImage
{
    /**
     * @var string
     */
    private $imageRoot;

    /**
     * @var \Swift_Message
     */
    private $swiftMessage;

    /**
     * SwiftHtmlImage constructor.
     * @param string $imageRoot The root dir to the images which should be embedded in the html.
     */
    public function __construct($imageRoot){
        $this->imageRoot = $imageRoot;
    }

    /**
     * @param \Swift_Message $message the swift mailer message
     * @param string $html the html in which the images should be embedded
     * @return string the html with embedded images
     */
    public function embedImages(\Swift_Message $swiftMessage, $html) : string {
        $this->swiftMessage = $swiftMessage;

        foreach($this->getImageList($html) as $relativeImagePath){
            $swiftMessageImage = $this->getSwiftMessageImage($relativeImagePath);
            $html = str_replace('"' . $relativeImagePath . '"', '"' . $swiftMessageImage . '"', $html);
        }

        return $html;
    }

    /**
     * @param string $html
     * @return array
     */
    private function getImageList($html) : array{
        preg_match_all('/<[iI][mM][gG](?:[^\]]*?\b)[sS][rR][cC](?:[\s]*?)=(?:[\s]*?)"(?!\/\/)(?!http:\/\/)(?!https:\/\/)(.*?)"/', $html, $matches);
        return array_unique($matches[1]);
    }

    /**
     * @param string $relativeImagePath
     * @return string
     */
    private function getSwiftMessageImage($relativeImagePath) : string {
        $imgPath = $this->imageRoot . DIRECTORY_SEPARATOR . $relativeImagePath;
        if(!file_exists($imgPath)){
            throw new SwiftHtmlImageException("The file " . $imgPath . " does not exist (relative path was " . $relativeImagePath . ")");
        }

        return $this->swiftMessage->embed(\Swift_Image::fromPath($imgPath));
    }
}