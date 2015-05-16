<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 2/19/15
 * Time: 5:00 PM
 */

namespace tests\RedTest\tests\test\crud\Fields\DecimalMultipleTextfield2;

use RedTest\core\entities\User;
use RedTest\core\Utils;
use RedTest\forms\entities\Node\TestForm;

/**
 * Drupal root directory.
 */
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_override_server_variables();
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

/**
 * Class AuthenticatedUserTest
 *
 * @package tests\RedTest\tests\test\crud\Fields\BooleanCheckboxes
 */
class AuthenticatedUserTest extends \PHPUnit_Framework_TestCase {

  /**
   * @var array
   */
  protected $backupGlobalsBlacklist = array('user', 'entities');

  /**
   * @var string
   */
  private static $field_name = 'field_decimal_multiple_text_2';

  /**
   * @var string
   */
  private static $fillFunctionName;

  /**
   * @var User
   */
  private static $userObject;

  /**
   * @var int
   */
  private static $nid;

  /**
   * @var array
   */
  private static $fields;

  /**
   * Create an authenticated user and log in.
   */
  public static function setupBeforeClass() {
    self::$fillFunctionName = 'fill' . Utils::makeTitleCase(
        self::$field_name
      ) . 'Values';

    list($success, $userObject, $msg) = User::createDefault();
    self::assertTrue($success, $msg);

    self::$userObject = User::loginProgrammatically($userObject->getId());
  }

  /**
   * Submit a new test form.
   */
  public function testEmptySubmission() {
    $testForm = new TestForm();

    self::$fields = self::getEmptyFieldValues();

    list($success, $values, $msg) = $testForm->fillDefaultTitleValues();
    $this->assertTrue($success, $msg);
    self::$fields['title'] = $values;

    list($success, $values, $msg) = $testForm->{self::$fillFunctionName}();
    $this->assertTrue($success, $msg);
    $this->assertEquals(
      array(),
      $values,
      "Values filled into " . self::$field_name . " are not correct."
    );
    self::$fields[self::$field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(self::$fields);
    $this->assertTrue($success, $msg);

    self::$nid = $nodeObject->getId();
  }

  /**
   * Edit the form and save it without changing any field value.
   *
   * @depends testEmptySubmission
   */
  public function testEmptySubmissionWithoutChange() {
    $testForm = new TestForm(self::$nid);

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(self::$fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Set the field value to 1 and submit the form.
   *
   * @depends testEmptySubmissionWithoutChange
   */
  public function testValueOneSubmission() {
    $testForm = new TestForm(self::$nid);

    list($success, $values, $msg) = $testForm->{self::$fillFunctionName}("35,78");
    $this->assertTrue($success, $msg);
    $this->assertEquals(
      35.78,
      $values,
      "Values filled into " . self::$field_name . " are not correct."
    );
    self::$fields[self::$field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(self::$fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Set the field value to both 0 and 1 and submit the form.
   *
   * @depends testValueOneSubmission
   */
  public function testValueMultipleSubmission() {
    $testForm = new TestForm(self::$nid);

    list($success, $values, $msg) = $testForm->{self::$fillFunctionName}(
      array(0.56, -9.67, "2,3")
    );
    $this->assertTrue($success, $msg);
    $this->assertEquals(
      array(0.56, -9.67, 2.3),
      $values,
      "Values filled into " . self::$field_name . " are not correct."
    );
    self::$fields[self::$field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(self::$fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Edit the form and save it without changing any field value.
   *
   * @depends testValueMultipleSubmission
   */
  public function testEmptySubmissionWithoutChange2() {
    $testForm = new TestForm(self::$nid);

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(self::$fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Edit the form, set the field to 0 and save the form.
   *
   * @depends testEmptySubmissionWithoutChange2
   */
  public function testValueZeroSubmission() {
    $testForm = new TestForm(self::$nid);

    list($success, $values, $msg) = $testForm->{self::$fillFunctionName}(array("2", 0));
    $this->assertTrue($success, $msg);
    $this->assertEquals(array(2, 0), $values, "Values filled into " . self::$field_name . " are not correct.");
    self::$fields[self::$field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(self::$fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Edit the form and make the field empty.
   *
   * @depends testValueZeroSubmission
   */
  public function testEmptySubmission2() {
    $testForm = new TestForm(self::$nid);

    list($success, $values, $msg) = $testForm->{self::$fillFunctionName}();
    $this->assertTrue($success, $msg);
    $this->assertEquals(array(), $values, "Values filled into " . self::$field_name . " are not correct.");
    self::$fields[self::$field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(self::$fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Returns an associative array with field names as keys and empty values.
   *
   * @return array
   *   Associative array with field names as keys.
   */
  private static function getEmptyFieldValues() {
    $instances = field_info_instances('node', 'test');
    $fields = array();
    foreach ($instances as $field_name => $value) {
      $fields[$field_name] = '';
    }
    $fields['title'] = '';

    return $fields;
  }

  /**
   * Log out and delete the entities created in this test.
   */
  public static function tearDownAfterClass() {
    self::$userObject->logout();
    Utils::deleteCreatedEntities();
  }
}