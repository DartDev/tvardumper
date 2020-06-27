<?php

namespace Drupal\tvardumper;

/**
 * Class TVarDumper.
 *
 * @package Drupal\tvardumper
 */
class TVarDumper implements TVarDumperInterface {

  /**
   * Objects.
   *
   * @var mixed
   */
  private static $objects;

  /**
   * Output string.
   *
   * @var string
   */
  private static $output;

  /**
   * Dump depth.
   *
   * @var int
   */
  private static $depth;

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
  public static function dump($var, $depth = 10, $filename = 'tvardump.txt') {
    self::$output = '';
    self::$objects = [];
    self::$depth = $depth;
    self::dumpInternal($var, 0);

    file_put_contents($filename, self::$output);
  }

  /**
   * Comment.
   *
   * @param mixed $var
   *   Comment.
   * @param int $level
   *   Comment.
   */
  private static function dumpInternal($var, $level) {
    switch (gettype($var)) {
      case 'boolean':
        self::$output .= $var ? 'true' : 'false';
        break;

      case 'integer':
        self::$output .= "$var";
        break;

      case 'double':
        self::$output .= "$var";
        break;

      case 'string':
        self::$output .= "'$var'";
        break;

      case 'resource':
        self::$output .= '{resource}';
        break;

      case 'NULL':
        self::$output .= 'null';
        break;

      case 'unknown type':
        self::$output .= '{unknown}';
        break;

      case 'array':
        if (self::$depth <= $level) {
          self::$output .= 'array(...)';
        }
        elseif (empty($var)) {
          self::$output .= 'array()';
        }
        else {
          $keys = array_keys($var);
          $spaces = str_repeat(' ', $level * 4);
          self::$output .= "array\n" . $spaces . '(';

          foreach ($keys as $key) {
            self::$output .= "\n" . $spaces . "    [$key] => ";
            self::dumpInternal($var[$key], $level + 1);
          }

          self::$output .= "\n" . $spaces . ')';
        }
        break;

      case 'object':
        if (($id = array_search($var, self::$objects, TRUE)) !== FALSE) {
          self::$output .= \get_class($var) . '#' . ($id + 1) . '(...)';
        }
        elseif (self::$depth <= $level) {
          self::$output .= \get_class($var) . '(...)';
        }
        else {
          $id = array_push(self::$objects, $var);
          $className = \get_class($var);
          $members = (array) $var;
          $keys = array_keys($members);
          $spaces = str_repeat(' ', $level * 4);
          self::$output .= "$className#$id\n" . $spaces . '(';

          foreach ($keys as $key) {
            $keyDisplay = strtr(trim($key), ["\0" => ':']);
            self::$output .= "\n" . $spaces . "    [$keyDisplay] => ";
            self::dumpInternal($members[$key], $level + 1);
          }

          self::$output .= "\n" . $spaces . ')';
        }
        break;
    }
  }

}
