<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/23/15
 * Time: 9:31 AM
 */

namespace RedTest\tests\views;

use RedTest\core\entities\User;
use RedTest\entities\Node\Test;
use RedTest\core\Utils;
use RedTest\core\View;

/**
 * Drupal root directory.
 */
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_override_server_variables(array('SERVER_SOFTWARE' => 'RedTest'));
//drupal_override_server_variables();
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


class ListOfTestNodesTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var array
   */
  protected $backupGlobalsBlacklist = array('user', 'entities', 'language', 'language_url', 'language_content');

  private static $testObjects;

  public static function setUpBeforeClass() {
    list($success, $userObject, $msg) = User::loginProgrammatically(1);
    self::assertTrue($success, $msg);

    list($success, self::$testObjects, $msg) = Test::createDefault(3);
    self::assertTrue($success, $msg);
    $userObject->logout();
  }

  /*public function testSuperUser() {
    list($success, $userObject, $msg) = User::loginProgrammatically(1);
    $this->assertTrue($success, $msg);

    $view = new View('list_of_test_nodes');

    $this->assertTrue($view->hasAccess(), "Superuser does not have access to List of Test Nodes view.");

    $this->assertEquals('list-of-test-nodes/*', $view->getUrl(), "URL of List of Test Nodes is not correct.");

    $results = $view->execute(array(807), array('type' => array('page', 'test')));
    $this->assertTrue($view->hasValues(array(array('nid' => 142, 'node_title' => 'Trial by Author'), array('nid' => 144)), FALSE, TRUE), "View values do not match.");

    $userObject->logout();
  }*/

  /**
   * Test the view as an authenticated user.
   */
  public function testAuthenticatedUser() {
    // Log the user out if he is logged in.
    User::logout();

    list($success, $userObject, $msg) = User::createDefault();
    $this->assertTrue($success, $msg);

    list($success, $userObject, $msg) = User::loginProgrammatically($userObject->getId());
    $this->assertTrue($success, $msg);

    //Utils::sort(self::$testObjects, "nid DESC");

    $view = new View('list_of_test_nodes');

    $this->assertFalse($view->hasAccess(), "Authenticated user has access to List of Test Nodes view.");

    $userObject->logout();
  }

  public static function tearDownAfterClass() {
    Utils::deleteCreatedEntities();
  }

}