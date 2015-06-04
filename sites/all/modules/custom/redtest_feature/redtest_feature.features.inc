<?php
/**
 * @file
 * redtest_feature.features.inc
 */

/**
 * Implements hook_node_info().
 */
function redtest_feature_node_info() {
  $items = array(
    'test' => array(
      'name' => t('Test'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
    'test_2' => array(
      'name' => t('Test 2'),
      'base' => 'node_content',
      'description' => '',
      'has_title' => '1',
      'title_label' => t('Title'),
      'help' => '',
    ),
  );
  drupal_alter('node_info', $items);
  return $items;
}
