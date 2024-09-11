<!--
id: readme
tags: ''
-->

# Glob

A replacement for http://www.php.net/manual/en/function.glob.php that provides support for `**`.

```php
$matched_paths = \AKlump\Glob\Glob::glob('/foo/**/*.txt');
```

In some cases you may be able to achieve better performance by reusing a single instance as shown below.  The reason is that each instance generates a file cache when it's first called.  The downside is that you MUST manage the cache yourself, that is, if the file system changes, you must use a new instance.  And that coordination is up to you.  **By using the static `::glob` method, you do not need to manage caching, as each call produces a new instance.**

```php
$glob = new \AKlump\Glob\Glob();
$matched_paths = $glob('/foo/**/*.txt');
// This second call will rely on the internal cache of $glob and is theoretically faster.
$matched_paths = $glob('/foo/**/*.md');
```

{{ composer.install|raw }}

## Why a New Package

This package was written to address unexpected results from the other available glob-replacement composer packages available at the time.
