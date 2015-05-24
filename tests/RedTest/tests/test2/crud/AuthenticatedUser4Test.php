<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 2/19/15
 * Time: 5:00 PM
 */

namespace tests\RedTest\tests\test2\crud;

use RedTest\core\entities\User;
use RedTest\core\Utils;
use RedTest\entities\Node\Test;
use RedTest\entities\Node\Test2;
use RedTest\forms\entities\Node\Test2Form;
use RedTest\forms\entities\Node\TestForm;
use RedTest\core\Menu;
use RedTest\core\View;

/**
 * Drupal root directory.
 */
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_override_server_variables();
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

class AuthenticatedUser4Test extends \PHPUnit_Framework_TestCase {

  protected $backupGlobalsBlacklist = array('user', 'entities');

  /**
   * @var User
   */
  private static $userObject;

  public static function setupBeforeClass() {
    list($success, $userObject, $msg) = User::createDefault();
    self::assertTrue($success, $msg);

    list($success, self::$userObject, $msg) = User::loginProgrammatically($userObject->getId());
    self::assertTrue($success, $msg);
  }

  public function testAllDefault() {
    $this->assertEquals(
      'node_add',
      Menu::getPageCallback('node/add/test-2'),
      "Page callback to add a Test node is incorrect."
    );

    $this->assertTrue(
      Test2::hasCreateAccess(),
      "Authenticated user does not have access to create a Test 2 node."
    );

    /*for ($i = 0; $i < 100; $i++) {
      $testForm = new Test2Form();

      list($success, $fields, $msg) = $testForm->fillDefaultValues();
      $this->assertTrue($success, $msg);

      list($success, $nodeObject, $msg) = $testForm->submit();
      $this->assertTrue($success, $msg);

      list($success, $msg) = $nodeObject->checkValues($fields);
      $this->assertTrue($success, $msg);
    }*/

    $testForm = new Test2Form();

    list($success, $fields, $msg) = $testForm->fillDefaultValues();
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

    list($success, $fields, $msg) = $testForm->fillDefaultValues();
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