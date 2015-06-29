<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/20/15
 * Time: 9:15 AM
 */

namespace RedTest\tests\test\crud\Fields\Templates;

use RedTest\core\entities\User;
use RedTest\core\RedTest_Framework_TestCase;
use RedTest\core\Utils;
use RedTest\forms\entities\Node\TestForm;


/**
 * Class AuthenticatedUser
 *
 * @package RedTest\tests\test\crud\Fields\Templates
 */
class AuthenticatedUser extends RedTest_Framework_TestCase {

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
  protected static $fillRandomFunctionName;

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

  /**
   * @var array
   */
  protected static $expectedValueEmpty = array();

  /**
   * @var int|string
   */
  protected static $valueOne = 1;

  /**
   * @var int|string
   */
  protected static $expectedValueOne = 1;

  /**
   * @var array
   */
  protected static $valueMultiple = array(1, 0);

  /**
   * @var array
   */
  protected static $expectedValueMultiple = array(1, 0);

  /**
   * @var array|int|string
   */
  protected static $valueZero = array(0);

  /**
   * @var int
   */
  protected static $expectedValueZero = 0;

  /**
   * Create an authenticated user and log in as that user.
   */
  public static function setUpBeforeClass() {
    static::$fillFunctionName = 'fill' . Utils::makeTitleCase(
        static::$field_name
      ) . 'Values';
    static::$fillRandomFunctionName = 'fill' . Utils::makeTitleCase(
        static::$field_name
      ) . 'Values';

    $userObject = User::createRandom()->verify(get_class());
    self::$userObject = User::loginProgrammatically($userObject->getId())
      ->verify(get_class());
  }

  /**
   * Submit a new test form.
   */
  public function testEmptySubmission() {
    $testForm = new TestForm();
    $testForm->verify($this);

    static::$fields = $this->getEmptyFieldValues();

    static::$fields['title'] = $testForm->fillTitleRandomValues()->verify(
      $this
    );

    $values = $testForm->{static::$fillFunctionName}()->verify($this);
    $this->assertEquals(
      static::$expectedValueEmpty,
      $values,
      "Values filled into " . static::$field_name . " are not correct."
    );
    static::$fields[static::$field_name] = $values;

    $nodeObject = $testForm->submit()->verify($this);

    $nodeObject->checkValues(static::$fields)->verify($this);

    static::$nid = $nodeObject->getId();
  }

  /**
   * Edit the form and save it without changing any field value.
   *
   * @depends testEmptySubmission
   */
  public function testEmptySubmissionWithoutChange() {
    $testForm = new TestForm(static::$nid);
    $testForm->verify($this);

    $nodeObject = $testForm->submit()->verify($this);

    $nodeObject->checkValues(static::$fields)->verify($this);
  }

  /**
   * Set the field value to 1 and submit the form.
   *
   * @depends testEmptySubmissionWithoutChange
   */
  public function testValueOneSubmission() {
    $testForm = new TestForm(static::$nid);
    $testForm->verify($this);

    $values = $testForm->{static::$fillFunctionName}(static::$valueOne)->verify(
      $this
    );
    $this->assertEquals(
      static::$expectedValueOne,
      $values,
      "Values filled into " . static::$field_name . " are not correct."
    );
    static::$fields[static::$field_name] = $values;

    $nodeObject = $testForm->submit()->verify($this);

    $nodeObject->checkValues(static::$fields)->verify($this);
  }

  /**
   * Set the field value to both 0 and 1 and submit the form.
   *
   * @depends testValueOneSubmission
   */
  public function testValueMultipleSubmission() {
    $testForm = new TestForm(static::$nid);
    $testForm->verify($this);

    $values = $testForm->{static::$fillFunctionName}(static::$valueMultiple)
      ->verify($this);
    $this->assertEquals(
      static::$expectedValueMultiple,
      $values,
      "Values filled into " . static::$field_name . " are not correct."
    );
    static::$fields[static::$field_name] = $values;

    $nodeObject = $testForm->submit()->verify($this);

    $nodeObject->checkValues(static::$fields)->verify($this);
  }

  /**
   * Edit the form and save it without changing any field value.
   *
   * @depends testValueMultipleSubmission
   */
  public function testEmptySubmissionWithoutChange2() {
    $testForm = new TestForm(static::$nid);
    $testForm->verify($this);

    $nodeObject = $testForm->submit()->verify($this);

    $nodeObject->checkValues(static::$fields)->verify($this);
  }

  /**
   * Edit the form, set the field to 0 and save the form.
   *
   * @depends testEmptySubmissionWithoutChange2
   */
  public function testValueZeroSubmission() {
    $testForm = new TestForm(static::$nid);
    $testForm->verify($this);

    $values = $testForm->{static::$fillFunctionName}(static::$valueZero)
      ->verify($this);
    $this->assertEquals(
      static::$expectedValueZero,
      $values,
      "Values filled into " . static::$field_name . " are not correct."
    );
    static::$fields[static::$field_name] = static::$expectedValueZero;

    $nodeObject = $testForm->submit()->verify($this);

    $nodeObject->checkValues(static::$fields)->verify($this);
  }

  /**
   * Edit the form and make the field empty.
   *
   * @depends testValueZeroSubmission
   */
  public function testEmptySubmission2() {
    $testForm = new TestForm(static::$nid);
    $testForm->verify($this);

    $values = $testForm->{static::$fillFunctionName}()->verify($this);
    $this->assertEquals(
      static::$expectedValueEmpty,
      $values,
      "Values filled into " . static::$field_name . " are not correct."
    );
    static::$fields[static::$field_name] = $values;

    $nodeObject = $testForm->submit()->verify($this);

    $nodeObject->checkValues(static::$fields)->verify($this);
  }

  /**
   * Edit the form and fill with default values.
   *
   * @depends testEmptySubmission2
   */
  public function testDefaltValues() {
    for ($i = 0; $i < 5; $i++) {
      $testForm = new TestForm(static::$nid);
      $testForm->verify($this);

      $values = $testForm->{static::$fillRandomFunctionName}()->verify($this);
      static::$fields[static::$field_name] = $values;

      $nodeObject = $testForm->submit()->verify($this);

      $nodeObject->checkValues(static::$fields)->verify($this);
    }
  }

  /**
   * Returns an associative array with field names as keys and empty values.
   *
   * @return array
   *   Associative array with field names as keys.
   */
  protected static function getEmptyFieldValues() {
    $instances = field_info_instances('node', 'test');
    $fields = array();
    foreach ($instances as $field_name => $value) {
      $fields[$field_name] = '';
    }
    $fields['title'] = '';

    return $fields;
  }
}