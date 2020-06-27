<?php

namespace Drupal\tvardumper;

/**
 * Interface TVarDumperInterface.
 *
 * @package Drupal\tvardumper
 */
interface TVarDumperInterface {

  /**
   * Converts a variable into a string representation.
   *
   * This method achieves the similar functionality as var_dump and print_r
   * but is more robust when handling complex objects such as PRADO controls.
   *
   * The generated output is dumped into the application root directory.
   *
   * @param mixed $var
   *   Variable to dump.
   * @param int $depth
   *   Maximum depth of dumping.
   * @param string $filename
   *   Name of the dump file.
   */
  public static function dump($var, $depth = 10, $filename = 'tvardump.txt');

}
