<!--
id: readme
tags: ''
-->

# Glob

A replacement for http://www.php.net/manual/en/function.glob.php that provides support for `**`.

```php
$matched_paths = \AKlump\Glob\Glob::glob('/foo/**/*.txt');
```

You can also create multiple, single instances, however each new instance has it's own, separate cache bin. Therefore in the example below, calling `$glob1` (which caches the filesystem) and then calling `$glob2` will take longer than calling `$glob1` a second time (because the filesystem will be re-cached by `$glob2`).

```php
$glob1 = new \AKlump\Glob\Glob();
$matched_paths = $glob1('/foo/**/*.txt');
$glob2 = new \AKlump\Glob\Glob();
$matched_paths = $glob2('/foo/**/*.txt');
```

{{ composer.install|raw }}

## Why a New Package

This package was written to address unexpected results from the other available glob-replacement composer packages available at the time.
