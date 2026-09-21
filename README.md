# glob

> A replacement for PHP's `glob()` that understands `**`, for recursive path matching without a shell call.

![glob](images/hero.jpg) <!-- FPO: replace with your image, then delete this comment -->

## Summary

PHP's own `glob()` stops at one directory level. There is no `**`, so finding every `.php` file under `src/` means writing a recursive walk or shelling out to `find`. This package adds the missing piece: `\AKlump\Glob\Glob::glob()` takes the patterns you already write and reads `**` as "any number of directories". Patterns without `**` are handed straight to the native `glob()`, so the common case costs nothing extra. Matches come back absolute and sorted, directories carry a trailing slash, and a matched symlink brings its target along with it. It exists because the other glob-replacement packages available at the time returned unexpected results.

## Quick Start

Install the package:

```bash
composer require aklump/glob:^0.0
```

Then match across directory levels:

```php
<?php
require 'vendor/autoload.php';

use AKlump\Glob\Glob;

print_r(Glob::glob('src/**/*.php'));
```

A relative pattern is resolved against the current working directory, and the matches come back absolute and sorted:

```text
Array
(
    [0] => /path/to/project/src/Filesystem/Path.php
    [1] => /path/to/project/src/Helpers/Cache.php
    [2] => /path/to/project/src/Helpers/GetConcretePaths.php
)
```

## Requirements

PHP 7.3 or newer. The unit tests are run against 7.3, 7.4, 8.0, 8.1 and 8.2 by `bin/run_multi_php_unit_tests.sh`.

Everything else arrives through Composer: `aklump/gitignore` for pattern matching, plus `symfony/filesystem`, `crysalead/dir` and `psr/simple-cache`. No PHP extension has to be enabled and there is no binary to install. Symfony's `Path` class did not exist before `symfony/filesystem` 5.4, so this package ships its own and aliases it in when Symfony's is absent; `^4` works as well as `^6.4`.

## Installation

```bash
composer require aklump/glob:^0.0
```

The package is autoloaded under the `AKlump\Glob\` namespace by PSR-4, so Composer's autoloader is the whole of the setup.

To work on the package itself:

```bash
git clone https://github.com/aklump/glob.git
cd glob
composer install
./vendor/bin/phpunit -c ./tests_unit/phpunit.xml
```

```text
OK (14 tests, 32 assertions)
```

`bin/run_unit_tests.sh` runs the same suite and writes an HTML coverage report to `reports/html`, which needs Xdebug or PCOV. `bin/run_multi_php_unit_tests.sh` runs it under every supported PHP version and needs `aklump/phpswap`.

## Usage

### One call at a time, or many

`Glob::glob()` builds a new instance for every call, and each instance builds its own file-list cache the first time it runs. That makes the static call safe by default: it can never hand you a stale listing.

When you are about to run several patterns against a tree that is not changing, reuse one instance and the cache is built once:

```php
$glob = new \AKlump\Glob\Glob();
$php = $glob('src/**/*.php');
// This second call reads the cache the first call built.
$md = $glob('docs/**/*.md');
```

The trade is yours to manage. An instance keeps its cache for as long as it lives, so if the file system changes underneath it, discard it and make a new one.

### Testing a path against a pattern

`Glob::match()` answers the same question without touching the disk:

```php
\AKlump\Glob\Glob::match('src/Helpers/Cache.php', 'src/**/*.php'); // TRUE
\AKlump\Glob\Glob::match('/foo/lorem', '/*/bar');                  // FALSE
```

### What the patterns mean

Patterns follow [gitignore's pattern format](https://git-scm.com/docs/gitignore#_pattern_format), which is where `**` comes from.

- `*` matches within a single directory level; `**` crosses any number of them.
- A relative pattern is made absolute against the current working directory before matching, so every returned path is absolute.
- A directory in the results ends in a slash, so you can tell it from a file without a second `is_dir()` call.
- When a match is a symlink, both the link and the path it resolves to appear in the results.

## License

[BSD-3-Clause](LICENSE)
