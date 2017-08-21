<?php

namespace Drupal\elm_drupal_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'ElmBlock' block.
 *
 * @Block(
 *  id = "elm_block",
 *  admin_label = @Translation("Elm block"),
 * )
 */
class ElmBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];
    $build['elm_block']['#markup'] = '<div id="elm-block"></div>';
    $build['elm_block']['#attached']['library'] = array( 'elm_drupal_block/elm_block_js', );

    return $build;
  }

}
