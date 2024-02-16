<!--
id: readme
tags: ''
-->

# Glob

A replacement for http://www.php.net/manual/en/function.glob.php that provides support for `**`.

```php
$matched_paths = \AKlump\Glob\Glob::glob('/foo/**/*.txt');

// You can also create single instances, which are less performant as they do not
// share a common file cache.
$glob = new \AKlump\Glob\Glob() 
$matched_paths = $glob('/foo/**/*.txt');
```

{{ composer_install|raw }}

## Other Packages

I have found that in some cases https://packagist.org/packages/webmozart/glob does not return the expected results, hence this package.
