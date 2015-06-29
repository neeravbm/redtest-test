<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 2/19/15
 * Time: 5:00 PM
 */

namespace RedTest\tests\test\crud;

use RedTest\core\entities\User;
use RedTest\core\RedTest_Framework_TestCase;
use RedTest\core\Utils;
use RedTest\entities\Node\Test;
use RedTest\forms\entities\Node\TestForm;
use RedTest\core\Menu;
use RedTest\entities\TaxonomyTerm\Tags;


class AuthenticatedUser3Test extends RedTest_Framework_TestCase {

  /**
   * @var User
   */
  private static $userObject;

  public static function setupBeforeClass() {
    $userObject = User::createRandom()->verify(get_class());

    self::$userObject = User::loginProgrammatically($userObject->getId())
      ->verify(get_class());
  }

  public function testAllRandom() {
    $this->assertEquals(
      'node_add',
      Menu::getPageCallback('node/add/test'),
      "Page callback to add a Test node is incorrect."
    );

    $this->assertTrue(
      Test::hasCreateAccess(),
      "Authenticated user does not have access to create a Test node."
    );

    $tagsObjects = Tags::createRandom(5)->verify($this);

    $testForm = new TestForm();
    $testForm->verify($this);

    $options = array(
      'required_fields_only' => FALSE,
      'references' => array(
        'taxonomy_terms' => array(
          'tags' => $tagsObjects,
        ),
      ),
    );

    $fields = $testForm->fillRandomValues($options)->verify($this);

    $nodeObject = $testForm->submit()->verify($this);

    $nodeObject->checkValues($fields)->verify($this);

    $testForm = new TestForm($nodeObject->getId());
    $testForm->verify($this);

    $nodeObject = $testForm->submit()->verify($this);

    $nodeObject->checkValues($fields)->verify($this);

    $testForm = new TestForm($nodeObject->getId());
    $testForm->verify($this);

    $fields = $testForm->fillRandomValues($options)->verify($this);

    $nodeObject = $testForm->submit()->verify($this);

    $nodeObject->checkValues($fields)->verify($this);

    $this->assertTrue(
      $nodeObject->hasViewAccess(),
      "Authenticated user does not have access to view a Test node."
    );

    $this->assertTrue(
      $nodeObject->hasUpdateAccess(),
      "Authenticated user does not have access to update a Test node."
    );

    $this->assertTrue(
      $nodeObject->hasDeleteAccess(),
      "Authenticated user does not have access to delete a Test node."
    );
  }

  /*public function testView() {
    $userObject = User::loginProgrammatically(1);
    list($success, $testObjects, $msg) = Test::createRandom(3);
    $this->assertTrue($success, $msg);
    $userObject->logout();

    list($success, $userObject, $msg) = User::createRandom();
    $this->assertTrue($success, $msg);

    $userObject = User::loginProgrammatically($userObject->getId());

    Utils::sort($testObjects, "nid DESC");

    $view = new View('list_of_test_nodes');
    //print_r($view->getUrl());
    print_r($view->hasAccess());
    $results = $view->execute(array(807), array('type' => array('page', 'test')));
    //print_r($view->getUrl());
    print_r($results);
    $this->assertTrue($view->hasValues(array(array('nid' => 142, 'node_title' => 'Trial by Author'), array('nid' => 144)), FALSE, TRUE), "View values do not match.");
    //print_r($view->hasAccess());
    $userObject->logout();
  }*/

  public static function tearDownAfterClass() {
    self::$userObject->logout();
    Utils::deleteCreatedEntities();
  }
}