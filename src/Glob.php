<?php

namespace AKlump\Glob;

use AKlump\Glob\Helpers\Cache;
use AKlump\Glob\Helpers\GetConcretePaths;

class Glob {

  private $cache;

  public function __construct() {
    $this->cache = new Cache();
  }

  /**
   * @param string $pattern
   * @param int $flags
   *
   * @return array|false
   */
  public function __invoke(string $path_pattern, int $flags = 0) {
    return (new GetConcretePaths($this->cache))($path_pattern);
  }

}
