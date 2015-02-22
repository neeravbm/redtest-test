<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 2/19/15
 * Time: 5:00 PM
 */

namespace tests\RedTest\tests\test\crud;

use RedTest\core\entities\User;
use RedTest\core\fields\Text;
use RedTest\core\Utilities;
use RedTest\entities\Node\Test;
use RedTest\forms\entities\Node\TestForm;

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
    /*list($success, $testObject, $msg) = Test::createDefault();
    $this->assertTrue(
      $success,
      "Authenticated user is not able to create a Test node: " . $msg
    );*/

    $testForm = new TestForm();
    list($success, $fields, $msg) = $testForm->fillDefaultValues(
      array('body')
    );
    $this->assertTrue(
      $success,
      "Authenticated user is not able to fill default fields: " . $msg
    );
    /*list($success, $values, $msg) = $testForm->fillDefaultBodyValues();
    $this->assertTrue(
      $success,
      "Authenticated user is not able to fill Body field: " . $msg
    );*/
    list($success, $values, $msg) = Text::fillDefaultValues($testForm, 'body');

    list($success, $values, $msg) = Text::fillDefaultTextTextAreaWithSummaryValues($testForm, 'body');

    $values = array(
      array(
        'value' => Utilities::getRandomText(100),
        'summary' => Utilities::getRandomText(25),
        'format' => 'php_code',
      )
    );
    list($success, $values, $msg) = $testForm->fillBody($values);

    list($success, $testObject, $errors) = $testForm->submit();
    $this->assertTrue(
      $success,
      "Authenticated user is not able to create a Test node: " . implode(
        ", ",
        $errors
      )
    );

  }

  public static function tearDownAfterClass() {
    self::$userObject->logout();
    Utilities::deleteCreatedEntities();
  }
}