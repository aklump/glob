# Glob

A replacement for http://www.php.net/manual/en/function.glob.php that provides support for `**`.

```php
$matched_paths = \AKlump\Glob\Glob::glob('/foo/**/*.txt');

// You can also create single instances, which are less performant as they do not
// share a common file cache.
$glob = new \AKlump\Glob\Glob() 
$matched_paths = $glob('/foo/**/*.txt');
```

## Install with Composer

1. Because this is an unpublished package, you must define it's repository in your project's _composer.json_ file. Add the following to _composer.json_:

    ```json
    "repositories": [
        {
            "type": "github",
            "url": "https://github.com/aklump/glob"
        }
    ]
    ```

1. Then `composer require aklump/glob:^0.0`    

## Other Packages

I have found that in some cases https://packagist.org/packages/webmozart/glob does not return the expected results, hence this package.
