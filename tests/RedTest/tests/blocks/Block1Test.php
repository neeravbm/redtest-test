<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/24/15
 * Time: 2:52 PM
 */

namespace RedTest\tests\blocks;

use RedTest\core\Block;
use RedTest\core\Menu;
use RedTest\core\entities\User;

/**
 * Drupal root directory.
 */
if (!defined('DRUPAL_ROOT')) {
  define('DRUPAL_ROOT', getcwd());
}
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_override_server_variables(array('SERVER_SOFTWARE' => 'RedTest'));
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


class Block1Test extends \PHPUnit_Framework_TestCase {

  /**
   * @var array
   */
  protected $backupGlobalsBlacklist = array('user', 'entities', 'language', 'language_url', 'language_content');

  public function testCustomBlock() {
    list($success, $userObject, $msg) = User::loginProgrammatically(1);
    $this->assertTrue($success, $msg);

    $blocks = Menu::getBlocks('a');

    $block = new Block('block_1');
    $this->assertTrue($block->isPresent('a'), "Block 1 is not present on /a path.");

    $block = new Block('block_2');
    $this->assertTrue($block->isPresent('a'), "Block 2 is not present on /a path.");

    $userObject->logout();
  }
}