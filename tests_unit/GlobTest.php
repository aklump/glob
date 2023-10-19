<?php

namespace AKlump\Glob\Tests\Unit;

use AKlump\Glob\Tests\Unit\TestWithFilesTrait;
use AKlump\Glob\Glob;

/**
 * @covers \AKlump\Glob\Glob
 */
class GlobTest extends \PHPUnit\Framework\TestCase {

  use TestWithFilesTrait;

  public function dataForInvokeProvider() {
    $tests = [];
    $tests[] = [
      '*',
      ['foo/'],
    ];
    $tests[] = [
      '**/bar/',
      ['foo/bar/'],
    ];
    $tests[] = [
      '**.txt',
      ['foo/bar/baz.txt'],
    ];

    return $tests;
  }

  /**
   * @dataProvider dataForInvokeProvider
   */
  public function testInvoke(string $relative_glob, array $expected_relative_paths) {
    $base = $this->getTestFilesDirectory();
    $files = (new Glob())($base . $relative_glob);
    $this->assertCount(count($expected_relative_paths), $files);
    foreach ($expected_relative_paths as $expected_path) {
      $this->assertContains($base . $expected_path, $files);
    }
  }

}
