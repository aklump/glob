<?php

namespace AKlump\Glob;

use AKlump\Glob\Helpers\Cache;
use AKlump\Glob\Helpers\GetConcretePaths;
use Symfony\Component\Filesystem\Path;

class Glob {

  private $cache;

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
