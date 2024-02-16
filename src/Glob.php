<?php

namespace AKlump\Glob;

use AKlump\Glob\Helpers\Cache;
use AKlump\Glob\Helpers\GetConcretePaths;
use Symfony\Component\Filesystem\Path;

class Glob {

  private $cache;

  private static $glob;

  public static function glob(string $path_pattern) {
    if (!isset(static::$glob)) {
      // I wonder if there is every a reason that a single static instance will
      // cause problems due to caching.  Do we need a way to clear this out,
      // maybe make it public?  I'll wait and see.
      static::$glob = new self();
    }

    return static::$glob->__invoke($path_pattern);
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
   * @return array|false
   */
  public function __invoke(string $path_pattern) {
    if (Path::isRelative($path_pattern)) {
      $this->makeAbsolute($path_pattern);
    }

    return (new GetConcretePaths($this->cache))($path_pattern);
  }

  private function makeAbsolute(string &$path_pattern) {
    $basePath = getcwd();
    if ($basePath) {
      $path_pattern = Path::makeAbsolute($path_pattern, $basePath);
    }
  }

}
