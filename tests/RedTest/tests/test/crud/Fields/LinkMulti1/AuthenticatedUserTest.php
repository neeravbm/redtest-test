<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/20/15
 * Time: 9:34 AM
 */

namespace RedTest\tests\test\crud\Fields\LinkMulti1;


use RedTest\forms\entities\Node\TestForm;
use RedTest\tests\test\crud\Fields\Templates\AuthenticatedUser;

class AuthenticatedUserTest extends AuthenticatedUser {

  protected static $field_name = 'field_link_multi_1';

  protected static $expectedValueEmpty = '';

  protected static $valueOne = 35.78;

  protected static $expectedValueOne = 35.78;

  protected static $valueMultiple = array(
    "http://redcrackle.com",
    array('url' => 'http://www.google.com'),
    array(
      'title' => 'Times of India',
      'url' => 'http://timesofindia.indiatimes.com'
    )
  );

  protected static $expectedValueMultiple = array(
    "http://redcrackle.com",
    'http://www.google.com',
    array(
      'title' => 'Times of India',
      'url' => 'http://timesofindia.indiatimes.com'
    )
  );

  protected static $valueZero = array("http://www.google.com", 0);

  protected static $expectedValueZero = "http://www.google.com";

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
    $this->assertEquals(static::$valueZero, $values, "Values filled into " . static::$field_name . " are not correct.");
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
}