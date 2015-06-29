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


class ListOfTestNodes1Test extends RedTest_Framework_TestCase {

  private static $testObjects;

  public static function setUpBeforeClass() {
    $userObject = User::loginProgrammatically(1)->verify(get_class());

    self::$testObjects = Test::createRandom(3)->verify(get_class());

    $userObject->logout();
  }

  public function testAuthenticatedUser() {
    $userObject = User::createRandom()->verify($this);

    $userObject = User::loginProgrammatically($userObject->getId())->verify(
      $this
    );

    /*Utils::sort(self::$testObjects, "nid DESC");

    $view = new View('list_of_test_nodes');
    //print_r($view->getUrl());
    print_r($view->hasAccess());
    $results = $view->execute(array(807), array('type' => array('page', 'test')));
    //print_r($view->getUrl());
    print_r($results);
    $this->assertTrue($view->hasValues(array(array('nid' => 142, 'node_title' => 'Trial by Author'), array('nid' => 144)), FALSE, TRUE), "View values do not match.");
    //print_r($view->hasAccess());
    $userObject->logout();*/

  }

  public static function tearDownAfterClass() {
    Utils::deleteCreatedEntities();
  }

}