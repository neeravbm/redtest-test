<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 1/26/15
 * Time: 9:18 AM
 */

namespace tests\RedTest\tests\article\crud;

use RedTest\core\entities\User;
use RedTest\core\Utilities;

/**
 * Drupal root directory.
 */
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_override_server_variables();
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

class AuthenticatedUserTest extends \PHPUnit_Framework_TestCase {
  public static function setupBeforeClass() {

    list($success, $userObject, $msg) = User::createDefault();

    /*$username = Utilities::getRandomString(5);
    $email = $username . '@' . Utilities::getRandomString(5) . '.com';
    $password = Utilities::getRandomString();
    $output = User::registerUser($username, $email, $password);*/
  }

  public function testMe() {
    $this->assertTrue(TRUE, "error");
  }
}