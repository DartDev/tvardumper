<?php

namespace Drupal\tvardumper;

/**
 * Class TVarDumperTwigExtension.
 *
 * @package Drupal\tvardumper
 */
class TVarDumperTwigExtension extends \Twig_Extension {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'tvardumper';
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return [
      new \Twig_SimpleFunction('tvardump', [$this, 'tvardump']),
    ];
  }

  /**
   * Dumps the variable.
   *
   * @param mixed $var
   *   Variable to dump.
   * @param int $depth
   *   Maximum depth of dumping.
   * @param string $filename
   *   Name of the dump file.
   */
  public function tvardump($var, $depth = 10, $filename = 'tvardump.txt') {
    \Drupal::service('tvardumper')->dump($var, $depth, $filename);
  }

}
