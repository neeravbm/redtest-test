<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 2/19/15
 * Time: 5:00 PM
 */

namespace tests\RedTest\tests\test2\crud;

use RedTest\core\entities\User;
use RedTest\core\RedTest_Framework_TestCase;
use RedTest\core\Utils;
use RedTest\entities\Node\Test2;
use RedTest\entities\TaxonomyTerm\Tags;
use RedTest\forms\entities\Node\Test2Form;
use RedTest\core\Path;


class AuthenticatedUserTest extends RedTest_Framework_TestCase {

  /**
   * @var User
   */
  private static $userObject;

  public static function setupBeforeClass() {
    $userObject = User::createRandom()->verify(get_class());

    self::$userObject = User::loginProgrammatically(
      $userObject->getId()
    )->verify(get_class());
  }

  public function testAllRandom() {
    $path = new Path('node/add/test-2');
    $this->assertEquals(
      'node_add',
      $path->getPageCallback(),
      "Page callback to add a Test node is incorrect."
    );

    $this->assertTrue(
      Test2::hasCreateAccess(),
      "Authenticated user does not have access to create a Test 2 node."
    );

    $tagsObjects = Tags::createRandom(5)->verify($this);

    $testForm = new Test2Form();
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

    $testForm = new Test2Form($nodeObject->getId());
    $testForm->verify($this);

    $nodeObject = $testForm->submit()->verify($this);

    $nodeObject->checkValues($fields)->verify($this);

    $testForm = new Test2Form($nodeObject->getId());
    $testForm->verify($this);

    $fields = $testForm->fillRandomValues($options)->verify($this);

    $nodeObject = $testForm->submit()->verify($this);

    $nodeObject->checkValues($fields)->verify($this);

    $this->assertTrue(
      $nodeObject->hasViewAccess(),
      "Authenticated user does not have access to view a Test node."
    );
  }
}