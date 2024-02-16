# Glob

A replacement for http://www.php.net/manual/en/function.glob.php that provides support for `**`.

```php
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

## Performance Considerations

It is more performant to reuse a single instance as the file list may be cached with each use. The following is most performant:

```php
$glob = new \AKlump\Glob\Glob();
$text_files = $glob('**/*.txt');
$markdown_files = $glob('**/*.md');
```

In contrast the following is less performant:

```php
$glob = new \AKlump\Glob\Glob();
$text_files = $glob('**/*.txt');

$glob2 = new \AKlump\Glob\Glob();
$markdown_files = $glob2('**/*.md');
```
