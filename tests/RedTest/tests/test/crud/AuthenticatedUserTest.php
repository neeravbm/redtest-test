<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 2/19/15
 * Time: 5:00 PM
 */

namespace tests\RedTest\tests\test\crud;

use RedTest\core\entities\User;
use RedTest\core\fields\Field;
use RedTest\core\fields\Text;
use RedTest\core\Utils;
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

  protected $backupGlobalsBlacklist = array('user', 'entities');

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

    self::$userObject = User::loginProgrammatically(
      $userObject->getEntity()->name
    );
  }

  //public function testCreation() {
    /**
     * @var Test $testObject
     */
    /*list($success, $testObject, $msg) = Test::createDefault();
    $this->assertTrue(
      $success,
      "Authenticated user is not able to create a Test node: " . $msg
    );*/

    /*$testForm = new TestForm();

    list($success, $fields, $msg) = $testForm->fillDefaultValuesExcept(
      array('field_long_text_summary_1')
    );
    $this->assertTrue(
      $success,
      "Authenticated user is not able to fill default fields: " . $msg
    );

    list($success, $fields, $msg) = $testForm->fillDefaultFieldLongTextSummary1Values(
    );

    list($success, $values, $msg) = $testForm->fillDefaultFieldValues(
      'field_long_text_summary_1'
    );

    list($success, $values, $msg) = Field::fillDefaultValues(
      $testForm,
      'field_long_text_summary_1'
    );

    list($success, $values, $msg) = Text::fillDefaultValues(
      $testForm,
      'field_long_text_summary_1'
    );

    list($success, $values, $msg) = Text::fillDefaultTextTextAreaWithSummaryValues(
      $testForm,
      'field_long_text_summary_1'
    );

    $values = array(
      array(
        'value' => Utils::getRandomText(100),
        'summary' => Utils::getRandomText(25),
        'format' => 'php_code',
      )
    );

    list($success, $msg) = $testForm->fillDefaultFieldLongTextSummary1($values);
    $this->assertTrue(
      $success,
      "Authenticated user is not able to fill Body field: " . $msg
    );

    list($success, $values, $msg) = $testForm->fillFieldValues(
      'field_long_text_summary_1',
      $values
    );

    list($success, $values, $msg) = Text::fillValues(
      $testForm,
      'field_long_text_summary_1',
      $values
    );

    $values = array(
      array(
        'value' => Utils::getRandomText(100),
        'summary' => Utils::getRandomText(25),
        'format' => 'filtered_html',
      )
    );
    list($success, $values, $msg) = Text::fillTextTextAreaWithSummaryValues(
      $testForm,
      'field_long_text_summary_1',
      $values
    );

    $this->assertTrue(
      $success,
      "Authenticated user is not able to fill Body field: " . $msg
    );

    list($success, $testObject, $errors) = $testForm->submit();
    $this->assertTrue(
      $success,
      "Authenticated user is not able to create a Test node:" . Utils::convertErrorArrayToString(
        $errors
      )
    );
  }*/

  public function testAllDefault() {
    list($success, $testObjects, $msg) = Test::createDefault(1);
  }

  public static function tearDownAfterClass() {
    self::$userObject->logout();
    //Utils::deleteCreatedEntities();
  }
}