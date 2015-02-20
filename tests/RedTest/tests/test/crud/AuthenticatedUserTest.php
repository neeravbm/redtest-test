<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 2/19/15
 * Time: 5:00 PM
 */

namespace tests\RedTest\tests\test\crud;

use RedTest\core\entities\User;
use RedTest\core\Utilities;
use RedTest\entities\Node\Test;

/**
 * Drupal root directory.
 */
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_override_server_variables();
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

class AuthenticatedUserTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var User
   */
  private static $userObject;

  public static function setupBeforeClass() {
    list($success, $userObject, $msg) = User::createDefault();
    self::assertTrue(
      $success,
      "Authenticated user could not be created: " . $msg
    );

    self::$userObject = User::loginProgrammatically($userObject->name);
  }

  public function testCreation() {
    /**
     * @var Test $testObject
     */
    list($success, $testObject, $msg) = Test::createDefault();
    $this->assertTrue(
      $success,
      "Authenticated user is not able to create a Test node: " . $msg
    );
  }

  public static function tearDownAfterClass() {
    self::$userObject->logout();
    Utilities::deleteCreatedEntities();
  }
}