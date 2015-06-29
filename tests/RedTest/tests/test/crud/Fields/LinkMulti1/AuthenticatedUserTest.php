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
      static::$valueZero,
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
}