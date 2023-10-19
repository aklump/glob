<?php

namespace AKlump\Glob\Traits;


use AKlump\GitIgnore\Analyzer;
use Symfony\Component\Filesystem\Path;
use InvalidArgumentException;

trait HasBasePathTrait {

  /**
   * @var string
   */
  private $basePath = '';

  public function __construct(string $base_path = '') {
    if ($base_path) {
      $this->setBasePath($base_path);
    }
  }

  public function getBasePath(): string {
    return $this->basePath;
  }

  public function setBasePath(string $base_path): self {
    if (empty($base_path)) {
      throw new InvalidArgumentException('$base_path cannot be empty.');
    }
    if (Analyzer::containsPattern($base_path)) {
      throw new InvalidArgumentException(sprintf('$base_path cannot contain unmatched patterns: %s', $base_path));
    }
    $this->basePath = Path::normalize($base_path);

    return $this;
  }

}
