# Glob

A replacement for http://www.php.net/manual/en/function.glob.php that provides support for `**`.

```php
$matched_paths = (new \AKlump\Glob\Glob())('/foo/**/*.txt');
```

## Performance Considerations

It is more performant to reuse a single instance as the filelist may be cached with each use. This is most performant:

```php
$globber = new \AKlump\Glob\Glob();
$text_files = $globber('**/*.txt');
$markdown_files = $globber('**/*.md');
```



