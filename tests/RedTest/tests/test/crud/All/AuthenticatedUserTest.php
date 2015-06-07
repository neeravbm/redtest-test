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
use RedTest\core\View;
use RedTest\entities\TaxonomyTerm\Tags;


class AuthenticatedUserTest extends RedTest_Framework_TestCase {

  /**
   * @var User
   */
  private static $userObject;

  /**
   * Create an authenticated user and log in as that user.
   */
  public static function setupBeforeClass() {
    list($success, $userObject, $msg) = User::createDefault();
    self::assertTrue($success, $msg);

    list($success, self::$userObject, $msg) = User::loginProgrammatically(
      $userObject->getId()
    );
    self::assertTrue($success, $msg);
  }

  public function testAllDefault() {
    $this->assertEquals(
      'node_add',
      Menu::getPageCallback('node/add/test'),
      "Page callback to add a Test node is incorrect."
    );

    $this->assertTrue(
      Test::hasCreateAccess(),
      "Authenticated user does not have access to create a Test node."
    );

    list($success, $tagsObjects, $msg) = Tags::createDefault(5);
    $this->assertTrue($success, $msg);

    $testForm = new TestForm();

    $options = array(
      'required_fields_only' => FALSE,
      'references' => array(
        'taxonomy_terms' => array(
          'tags' => $tagsObjects,
        ),
      ),
    );

    list($success, $fields, $msg) = $testForm->fillDefaultValues(
      $options
    );
    $this->assertTrue($success, $msg);

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($fields);
    $this->assertTrue($success, $msg);

    $testForm = new TestForm($nodeObject->getId());

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($fields);
    $this->assertTrue($success, $msg);

    $testForm = new TestForm($nodeObject->getId());

    list($success, $fields, $msg) = $testForm->fillDefaultValues(
      $options
    );
    $this->assertTrue($success, $msg);

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($fields);
    $this->assertTrue($success, $msg);

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
    list($success, $testObjects, $msg) = Test::createDefault(3);
    $this->assertTrue($success, $msg);
    $userObject->logout();

    list($success, $userObject, $msg) = User::createDefault();
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
}