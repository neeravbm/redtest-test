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
use RedTest\entities\Node\Test;
use RedTest\entities\Node\Test2;
use RedTest\forms\entities\Node\Test2Form;
use RedTest\forms\entities\Node\TestForm;
use RedTest\core\Path;
use RedTest\core\View;


class AuthenticatedUser2Test extends RedTest_Framework_TestCase {

  /**
   * @var User
   */
  private static $userObject;

  public static function setupBeforeClass() {
    list($success, $userObject, $msg) = User::createRandom();
    self::assertTrue($success, $msg);

    list($success, self::$userObject, $msg) = User::loginProgrammatically(
      $userObject->getId()
    );
    self::assertTrue($success, $msg);
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

    /*for ($i = 0; $i < 100; $i++) {
      $testForm = new Test2Form();

      list($success, $fields, $msg) = $testForm->fillRandomValues();
      $this->assertTrue($success, $msg);

      list($success, $nodeObject, $msg) = $testForm->submit();
      $this->assertTrue($success, $msg);

      list($success, $msg) = $nodeObject->checkValues($fields);
      $this->assertTrue($success, $msg);
    }*/

    $testForm = new Test2Form();

    list($success, $fields, $msg) = $testForm->fillRandomValues();
    $this->assertTrue($success, $msg);

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($fields);
    $this->assertTrue($success, $msg);

    $testForm = new Test2Form($nodeObject->getId());

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($fields);
    $this->assertTrue($success, $msg);

    $testForm = new Test2Form($nodeObject->getId());

    list($success, $fields, $msg) = $testForm->fillRandomValues();
    $this->assertTrue($success, $msg);

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($fields);
    $this->assertTrue($success, $msg);

    $this->assertTrue(
      $nodeObject->hasViewAccess(),
      "Authenticated user does not have access to view a Test node."
    );
  }

  public static function tearDownAfterClass() {
    self::$userObject->logout();
    //Utils::deleteCreatedEntities();
  }
}