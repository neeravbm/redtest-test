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

class AuthenticatedUser extends \PHPUnit_Framework_TestCase {

  /**
   * @var array
   */
  protected $backupGlobalsBlacklist = array('user', 'entities', 'language', 'language_url', 'language_content');

  /**
   * @var string
   */
  protected $field_name;

  /**
   * @var string
   */
  protected $fillFunctionName;

  /**
   * @var string
   */
  protected $fillDefaultFunctionName;

  /**
   * @var User
   */
  protected $userObject;

  /**
   * @var int
   */
  protected $nid;

  /**
   * @var array
   */
  protected $fields;

  public function __construct($field_name) {
    $this->field_name = $field_name;
    $this->fillFunctionName = 'fill' . Utils::makeTitleCase($this->field_name) . 'Values';
    $this->fillDefaultFunctionName = 'fill' . Utils::makeTitleCase($this->field_name) . 'Values';

    list($success, $userObject, $msg) = User::createDefault();
    self::assertTrue($success, $msg);

    $this->userObject = User::loginProgrammatically($userObject->getId());
  }

  /**
   * Create an authenticated user and log in.
   */
  /*public static function setupBeforeClass() {
  }*/

  /**
   * Submit a new test form.
   */
  public function testEmptySubmission() {
    $testForm = new TestForm();

    $this->fields = $this->getEmptyFieldValues();

    list($success, $values, $msg) = $testForm->fillDefaultTitleValues();
    $this->assertTrue($success, $msg);
    $this->fields['title'] = $values;

    list($success, $values, $msg) = $testForm->{$this->fillFunctionName}();
    $this->assertTrue($success, $msg);
    $this->assertEquals(
      array(),
      $values,
      "Values filled into " . $this->field_name . " are not correct."
    );
    $this->fields[$this->field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($this->fields);
    $this->assertTrue($success, $msg);

    $this->nid = $nodeObject->getId();
  }

  /**
   * Edit the form and save it without changing any field value.
   *
   * @depends testEmptySubmission
   */
  public function testEmptySubmissionWithoutChange() {
    $testForm = new TestForm($this->nid);

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($this->fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Set the field value to 1 and submit the form.
   *
   * @depends testEmptySubmissionWithoutChange
   */
  public function testValueOneSubmission() {
    $testForm = new TestForm($this->nid);

    list($success, $values, $msg) = $testForm->{$this->fillFunctionName}(1);
    $this->assertTrue($success, $msg);
    $this->assertEquals(
      1,
      $values,
      "Values filled into " . $this->field_name . " are not correct."
    );
    $this->fields[$this->field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($this->fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Set the field value to both 0 and 1 and submit the form.
   *
   * @depends testValueOneSubmission
   */
  public function testValueMultipleSubmission() {
    $testForm = new TestForm($this->nid);

    list($success, $values, $msg) = $testForm->{$this->fillFunctionName}(
      array(1, 0)
    );
    $this->assertTrue($success, $msg);
    $this->assertEquals(
      array(1, 0),
      $values,
      "Values filled into " . $this->field_name . " are not correct."
    );
    $this->fields[$this->field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($this->fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Edit the form and save it without changing any field value.
   *
   * @depends testValueMultipleSubmission
   */
  public function testEmptySubmissionWithoutChange2() {
    $testForm = new TestForm($this->nid);

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($this->fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Edit the form, set the field to 0 and save the form.
   *
   * @depends testEmptySubmissionWithoutChange2
   */
  public function testValueZeroSubmission() {
    $testForm = new TestForm($this->nid);

    list($success, $values, $msg) = $testForm->{$this->fillFunctionName}(array(0));
    $this->assertTrue($success, $msg);
    $this->assertEquals(0, $values, "Values filled into " . $this->field_name . " are not correct.");
    $this->fields[$this->field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($this->fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Edit the form and make the field empty.
   *
   * @depends testValueZeroSubmission
   */
  public function testEmptySubmission2() {
    $testForm = new TestForm($this->nid);

    list($success, $values, $msg) = $testForm->{$this->fillFunctionName}();
    $this->assertTrue($success, $msg);
    $this->assertEquals(array(), $values, "Values filled into " . $this->field_name . " are not correct.");
    $this->fields[$this->field_name] = $values;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues($this->fields);
    $this->assertTrue($success, $msg);
  }

  /**
   * Edit the form and fill with default values.
   *
   * @depends testEmptySubmission2
   */
  public function testDefaltValues() {
    for ($i = 0; $i < 5; $i++) {
      $testForm = new TestForm($this->nid);

      list($success, $values, $msg) = $testForm->{$this->fillDefaultFunctionName}();
      $this->assertTrue($success, $msg);
      $this->fields[$this->field_name] = $values;

      list($success, $nodeObject, $msg) = $testForm->submit();
      $this->assertTrue($success, $msg);

      list($success, $msg) = $nodeObject->checkValues($this->fields);
      $this->assertTrue($success, $msg);
    }

    $this->userObject->logout();
    Utils::deleteCreatedEntities();
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
  /*public static function tearDownAfterClass() {
  }*/
}