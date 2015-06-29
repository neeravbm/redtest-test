<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/23/15
 * Time: 9:31 AM
 */

namespace RedTest\tests\views;

use RedTest\core\entities\User;
use RedTest\core\RedTest_Framework_TestCase;
use RedTest\entities\Node\Test;
use RedTest\core\Utils;
use RedTest\core\View;


class ListOfTestNodesTest extends RedTest_Framework_TestCase {

  private static $testObjects;

  public static function setUpBeforeClass() {
    $userObject = User::loginProgrammatically(1)->verify(get_class());

    self::$testObjects = Test::createRandom(3)->verify(get_class());

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

    $userObject = User::createRandom()->verify($this);

    $userObject = User::loginProgrammatically($userObject->getId())->verify($this);

    //Utils::sort(self::$testObjects, "nid DESC");

    $view = new View('list_of_test_nodes');

    $this->assertFalse($view->hasAccess(), "Authenticated user has access to List of Test Nodes view.");

    $userObject->logout();
  }

  public static function tearDownAfterClass() {
    Utils::deleteCreatedEntities();
  }

}