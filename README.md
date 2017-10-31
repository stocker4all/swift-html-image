# swift-html-image

[![Build Status](https://travis-ci.org/stocker4all/swift-html-image.svg?branch=master)](https://travis-ci.org/stocker4all/swift-html-image.svg?branch=master) [![Latest Stable Version](https://poser.pugx.org/stocker4all/swift-html-image/version)](https://packagist.org/packages/stocker4all/swift-html-image) [![License](https://poser.pugx.org/stocker4all/swift-html-image/license)](https://packagist.org/packages/stocker4all/swift-html-image) [![Total Downloads](https://poser.pugx.org/stocker4all/swift-html-image/downloads.png)](https://packagist.org/packages/stocker4all/swift-html-image)

`swift-html-image` is a very simple solution to auto-embed image sources with a relative path inside html for further use in Swift Mailer Message.

For example:

```html
<html>
<title>stocker4all/swift-html-image</title>
<body>
  <h1>swift-html-image</h1>

  <img src = "img/example.jpg">
  <img src = "//example.com/example.jpg">
  <img src = "http://example.com/example.jpg">
  <img src = "https://example.com/example.jpg">

</body>
</html>
```

Will be converted into something similar like this:

```html

<html>
<title>swift-html-image</title>
<body>
  <h1>swift-html-image</h1>

  <img src="cid:cbb8136c77eade53667df53661e2a973@localhost">
  <img src="//example.com/example.jpg">
  <img src="http://example.com/example.jpg">
  <img src="https://example.com/example.jpg">

</body>
</html>
```

Note that only the image with the relative path has become a `cid` value as source.

If an image with relative path can not be found an `SwiftHtmlImageException` will be thrown.

## Installing

You can use [Composer](http://getcomposer.org/) to add the [package](https://packagist.org/packages/stocker4all/swift-html-image) to your project:

```json
{
  "require": {
    "stocker4all/swift-html-image": "~0.1"
  }
}
```

## Usage example

```php
$html = '<!-- Your HTML goes here -->';

$swiftMessage = new Swift_Message();

$swiftHtmlImage = new S4A\SwiftHtmlImage( '/path-to-your-image-root-folder' );

$swiftMessage->setBody(
    $swiftHtmlImage->embedImages($swiftMessage, $html),
    "text/html",
    "utf-8");
```

## Tests

Some very basic tests are provided in the `tests/` directory. Run them with `composer install --dev && vendor/bin/phpunit`.

## License

`swift-html-image` is licensed under [MIT](LICENSE.md)