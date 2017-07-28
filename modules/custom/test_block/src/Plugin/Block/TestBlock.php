<?php

/**
 * @file
 * Contains \Drupal\test_block\Plugin\Block\TestBlock.
 */


namespace Drupal\test_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Добавляем простой блок с текстом.
 * Ниже - аннотация, она также обязательна.
 *
 * @Block(
 *   id = "test_block",
 *   admin_label = @Translation("Test block example"),
 * )
 */
class TestBlock extends BlockBase{

  /**
   * {@inheritdoc}
   */
  public function build() {
    $block = [
      '#type' => 'markup',
      '#markup' => '<strong>Hello World!</strong>'
    ];
    return $block;
  }

}
