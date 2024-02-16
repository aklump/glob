<!--
id: readme
tags: ''
-->

# Glob

A replacement for http://www.php.net/manual/en/function.glob.php that provides support for `**`.

```php
$glob = new \AKlump\Glob\Glob() 
$matched_paths = $glob('/foo/**/*.txt');
```

{{ composer_install|raw }}

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
