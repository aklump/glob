<?php

namespace AKlump\Glob\Filesystem;

use Symfony\Component\Filesystem\Filesystem;

/**
 * This is a Shim so we can use Filesystem version 4.
 */
class Path {

  public static function isAbsolute($path_pattern) {
    return (new Filesystem())->isAbsolutePath($path_pattern);
  }

  public static function makeAbsolute($relative_path, $base_path) {
    if (!(new Filesystem())->isAbsolutePath($relative_path)) {
      $relative_path = rtrim($base_path, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . ltrim($relative_path, DIRECTORY_SEPARATOR);
    }

    return $relative_path;
  }

  public static function normalize(string $path): string {
    return str_replace('\\', '/', $path);
  }

  public static function isRelative(string $path_pattern): bool {
    return !(new Filesystem())->isAbsolutePath($path_pattern);
  }

}
