<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/24/15
 * Time: 2:52 PM
 */

namespace RedTest\tests\blocks;

use RedTest\core\Block;
use RedTest\core\entities\User;

/**
 * Drupal root directory.
 */
if (!defined('DRUPAL_ROOT')) {
  define('DRUPAL_ROOT', getcwd());
}
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
// We need to provide a non-empty SERVER_SOFTWARE so that execution doesn't get
// treated as command-line execution by drupal_is_cli() function. If it is
// treated as command-line execution, then drupal_session_start() doesn't invoke
// session_start(). As a result, session_destroy() in User::logout() function
// throws an error. Although this does not affect RedTest execution or even
// session handling, it's better to not let Drupal throw this error in the first
// place.
if (empty($_SERVER['SERVER_SOFTWARE'])) {
  drupal_override_server_variables(array('SERVER_SOFTWARE' => 'RedTest'));
}
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


class AuthenticatedUserTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var array
   */
  protected $backupGlobalsBlacklist = array('user', 'entities', 'language', 'language_url', 'language_content');

  public static function setUpBeforeClass() {
    list($success, $userObject, $msg) = User::createDefault();
    self::assertTrue($success, $msg);


    list($success, $userObject, $msg) = User::loginProgrammatically($userObject->getId());
    self::assertTrue($success, $msg);
  }

  public function testBlock1() {
    $block = new Block('block_1');
    $this->assertTrue($block->isPresent('a'), "Block 1 is not present on /a path.");
    $this->assertTrue($block->isPresent('<front>'), "Block 1 not present on the homepage.");
  }

  public function testBlock2() {
    $block = new Block('block_2');
    $this->assertFalse($block->isPresent('a'), "Block 2 is present on /a path.");
    $this->assertTrue($block->isPresent('<front>'), "Block 2 is not present on the homepage.");
  }

  public static function tearDownAfterClass() {
    User::logout();
  }
}