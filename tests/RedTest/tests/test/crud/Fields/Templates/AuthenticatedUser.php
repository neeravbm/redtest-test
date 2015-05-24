<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/20/15
 * Time: 9:15 AM
 */

namespace RedTest\tests\test\crud\Fields\Templates;

use RedTest\core\entities\User;
use RedTest\core\Utils;
use RedTest\forms\entities\Node\TestForm;

/**
 * Drupal root directory.
 */
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_override_server_variables(array('SERVER_SOFTWARE' => 'RedTest'));
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

class AuthenticatedUser extends \PHPUnit_Framework_TestCase {

  /**
   * @var array
   */
  protected $backupGlobalsBlacklist = array('user', 'entities', 'language', 'language_url', 'language_content');

  /**
   * @var object
   */
  protected static $transaction;

  /**
   * @var string
   */
  protected static $field_name;

  /**
   * @var string
   */
  protected static $fillFunctionName;

  /**
   * @var string
   */
  protected static $fillDefaultFunctionName;

  /**
   * @var User
   */
  protected static $userObject;

  /**
   * @var int
   */
  protected static $nid;

  /**
   * @var array
   */
  protected static $fields;

  protected static $expectedValueEmpty = array();

  protected static $valueOne = 1;

  protected static $expectedValueOne = 1;

  protected static $valueMultiple = array(1, 0);

  protected static $expectedValueMultiple = array(1, 0);

  protected static $valueZero = array(0);

  protected static $expectedValueZero = 0;

  public static function setUpBeforeClass() {
    static::$fillFunctionName = 'fill' . Utils::makeTitleCase(static::$field_name) . 'Values';
    static::$fillDefaultFunctionName = 'fill' . Utils::makeTitleCase(static::$field_name) . 'Values';

    //static::$transaction = db_transaction();

    list($success, $userObject, $msg) = User::createDefault();
    static::assertTrue($success, $msg);

    list($success, self::$userObject, $msg) = User::loginProgrammatically($userObject->getId());
    self::assertTrue($success, $msg);
  }

  /**
   * Submit a new test form.
   */
  public function testEmptySubmission() {
    $testForm = new TestForm();

    static::$fields = $this->getEmptyFieldValues();

    list($success, $values, $msg) = $testForm->fillDefaultTitleValues();
    $this->assertTrue($success, $msg);
    static::$fields['title'] = $values;

    list($success, $values, $msg) = $testForm->{static::$fillFunctionName}();
    $this->assertTrue($success, $msg);
    $this->assertEquals(
      static::$expectedValueEmpty,
      $values,
      "Values filled into " . static::$field_name . " are not correct."
    );
    static::$fields[static::$field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(static::$fields);
    $this->assertTrue($success, $msg);

    static::$nid = $nodeObject->getId();
  }

  /**
   * Edit the form and save it without changing any field value.
   *
   * @depends testEmptySubmission
   */
  public function testEmptySubmissionWithoutChange() {
    $testForm = new TestForm(static::$nid);

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(static::$fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Set the field value to 1 and submit the form.
   *
   * @depends testEmptySubmissionWithoutChange
   */
  public function testValueOneSubmission() {
    $testForm = new TestForm(static::$nid);

    list($success, $values, $msg) = $testForm->{static::$fillFunctionName}(static::$valueOne);
    $this->assertTrue($success, $msg);
    $this->assertEquals(
      static::$expectedValueOne,
      $values,
      "Values filled into " . static::$field_name . " are not correct."
    );
    static::$fields[static::$field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(static::$fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Set the field value to both 0 and 1 and submit the form.
   *
   * @depends testValueOneSubmission
   */
  public function testValueMultipleSubmission() {
    $testForm = new TestForm(static::$nid);

    list($success, $values, $msg) = $testForm->{static::$fillFunctionName}(static::$valueMultiple);
    $this->assertTrue($success, $msg);
    $this->assertEquals(
      static::$expectedValueMultiple,
      $values,
      "Values filled into " . static::$field_name . " are not correct."
    );
    static::$fields[static::$field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(static::$fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Edit the form and save it without changing any field value.
   *
   * @depends testValueMultipleSubmission
   */
  public function testEmptySubmissionWithoutChange2() {
    $testForm = new TestForm(static::$nid);

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(static::$fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Edit the form, set the field to 0 and save the form.
   *
   * @depends testEmptySubmissionWithoutChange2
   */
  public function testValueZeroSubmission() {
    $testForm = new TestForm(static::$nid);

    list($success, $values, $msg) = $testForm->{static::$fillFunctionName}(static::$valueZero);
    $this->assertTrue($success, $msg);
    //$this->assertEquals(static::$expectedValueZero, $values, "Values filled into " . static::$field_name . " are not correct.");
    static::$fields[static::$field_name] = static::$expectedValueZero;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(static::$fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Edit the form and make the field empty.
   *
   * @depends testValueZeroSubmission
   */
  public function testEmptySubmission2() {
    $testForm = new TestForm(static::$nid);

    list($success, $values, $msg) = $testForm->{static::$fillFunctionName}();
    $this->assertTrue($success, $msg);
    $this->assertEquals(static::$expectedValueEmpty, $values, "Values filled into " . static::$field_name . " are not correct.");
    static::$fields[static::$field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(static::$fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Edit the form and fill with default values.
   *
   * @depends testEmptySubmission2
   */
  public function testDefaltValues() {
    for ($i = 0; $i < 5; $i++) {
      $testForm = new TestForm(static::$nid);

      list($success, $values, $msg) = $testForm->{static::$fillDefaultFunctionName}();
      $this->assertTrue($success, $msg);
      static::$fields[static::$field_name] = $values;

      list($success, $nodeObject, $msg) = $testForm->submit();
      $this->assertTrue($success, $msg);

      list($success, $msg) = $nodeObject->checkValues(static::$fields);
      $this->assertTrue($success, $msg);
    }
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
    static::$userObject->logout();
    Utils::deleteCreatedEntities();

    //static::$transaction->rollback();
  }
}