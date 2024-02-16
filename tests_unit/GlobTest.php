<?php

namespace AKlump\Glob\Tests\Unit;

use AKlump\Glob\Tests\Unit\TestWithFilesTrait;
use AKlump\Glob\Glob;

/**
 * @covers \AKlump\Glob\Glob
 */
class GlobTest extends \PHPUnit\Framework\TestCase {

  use TestWithFilesTrait;

  public function dataFortestMatchProvider() {
    $tests = [];
    $tests[] = [
      TRUE,
      '/foo/bar',
      '/*/bar',
    ];
    $tests[] = [
      FALSE,
      '/foo/lorem',
      '/*/bar',
    ];

    return $tests;
  }

  /**
   * @dataProvider dataFortestMatchProvider
   */
  public function testMatch(bool $expected, string $url, string $glob) {
    $result = Glob::match($url, $glob);
    $this->assertSame($expected, $result);
  }

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
  public function testInvokeWithAbsolutePaths(string $relative_glob, array $expected_relative_paths) {
    $base = $this->getTestFilesDirectory();
    $files = (new Glob())($base . $relative_glob);
    $this->assertCount(count($expected_relative_paths), $files);
    foreach ($expected_relative_paths as $expected_path) {
      $this->assertContains($base . $expected_path, $files);
    }
  }

  /**
   * @dataProvider dataForInvokeProvider
   */
  public function testGlobStaticMethodWithAbsolutePaths(string $relative_glob, array $expected_relative_paths) {
    $base = $this->getTestFilesDirectory();
    $files = Glob::glob($base . $relative_glob);
    $this->assertCount(count($expected_relative_paths), $files);
    foreach ($expected_relative_paths as $expected_path) {
      $this->assertContains($base . $expected_path, $files);
    }
  }

  /**
   * @dataProvider dataForInvokeProvider
   */
  public function testInvokeWithRelativePaths(string $relative_glob, array $expected_relative_paths) {
    $base = $this->getTestFilesDirectory();
    $this->assertTrue(chdir($base));
    $files = (new Glob())($relative_glob);
    $this->assertCount(count($expected_relative_paths), $files);
    foreach ($expected_relative_paths as $expected_path) {
      $this->assertContains($base . $expected_path, $files);
    }
  }

  /**
   * @dataProvider dataForInvokeProvider
   */
  public function testStaticGlobMethodWithRelativePaths(string $relative_glob, array $expected_relative_paths) {
    $base = $this->getTestFilesDirectory();
    $this->assertTrue(chdir($base));
    $files = Glob::glob($relative_glob);
    $this->assertCount(count($expected_relative_paths), $files);
    foreach ($expected_relative_paths as $expected_path) {
      $this->assertContains($base . $expected_path, $files);
    }
  }

}
