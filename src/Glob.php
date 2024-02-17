<?php

namespace AKlump\Glob;

use AKlump\GitIgnore\Pattern;
use AKlump\Glob\Helpers\Cache;
use AKlump\Glob\Helpers\GetConcretePaths;
use Symfony\Component\Filesystem\Path;

class Glob {

  private $cache;

  private static $glob;

  /**
   * Locates files in the CWD based on $path_pattern
   *
   * @param string $path_pattern The glob pattern to search CWD with.
   *
   * @return array
   */
  public static function glob(string $path_pattern): array {
    if (!isset(static::$glob)) {
      // I wonder if there is every a reason that a single static instance will
      // cause problems due to caching.  Do we need a way to clear this out,
      // maybe make it public?  I'll wait and see.
      static::$glob = new self();
    }

    return static::$glob->__invoke($path_pattern);
  }

  /**
   * Matches a given URL against a pattern.
   *
   * @param string $subject The path to match against the pattern.
   * @param string $glob The pattern to match with.
   *
   * @return bool Returns true if the URL matches the provided pattern, false otherwise.
   */
  public static function match(string $subject, string $glob): bool {
    return (new Pattern($glob))->matches($subject);
  }

  public function __construct() {
    $this->cache = new Cache();
  }

  /**
   * Convert a glob path to an array of absolute paths.
   *
   * @param string $pattern
   *   The can be absolute or relative.
   *
   * @return array
   */
  public function __invoke(string $path_pattern): array {
    if (Path::isRelative($path_pattern)) {
      $this->makeAbsolute($path_pattern);
    }

    if ($this->canUseCoreFunction($path_pattern)) {
      // This is more performant, and we use it if we can.
      return glob($path_pattern, GLOB_MARK);
    }

    return (new GetConcretePaths($this->cache))($path_pattern);
  }

  /**
   * Checks if a given glob pattern is supported by the native function.
   *
   * @param string $path_pattern The path pattern to check.
   *
   * @return bool Returns true if glob() can be used.
   */
  private function canUseCoreFunction(string $path_pattern): bool {
    return !strstr($path_pattern, '**');
  }

  private function makeAbsolute(string &$path_pattern) {
    $basePath = getcwd();
    if ($basePath) {
      $path_pattern = Path::makeAbsolute($path_pattern, $basePath);
    }
  }

}
