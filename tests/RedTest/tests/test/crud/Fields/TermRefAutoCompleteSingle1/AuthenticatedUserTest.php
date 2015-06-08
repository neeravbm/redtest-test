<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/20/15
 * Time: 9:34 AM
 */

namespace RedTest\tests\test\crud\Fields\TermRefAutoCompleteSingle1;


use RedTest\core\entities\TaxonomyTerm;
use RedTest\tests\test\crud\Fields\Templates\AuthenticatedUser;
use RedTest\core\Utils;
use RedTest\forms\entities\Node\TestForm;

class AuthenticatedUserTest extends AuthenticatedUser {

  protected static $field_name = 'field_term_ref_autocomp_single_1';

  protected static $expectedValueEmpty = '';

  protected static $valueOne = 'term 1';

  protected static $expectedValueOne = 'term 1';

  protected static $valueMultiple = 'term 2';

  protected static $expectedValueMultiple = 'term 2';

  protected static $valueZero = 0;

  protected static $expectedValueZero = 0;

  /**
   * Submit a new test form.
   */
  public function testEmptySubmission() {
    $testForm = new TestForm();

    static::$fields = $this->getEmptyFieldValues();

    list($success, $values, $msg) = $testForm->fillTitleRandomValues();
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

    static::$valueOne = static::$expectedValueOne = TaxonomyTerm::getUniqueName('tags');
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

    static::$valueMultiple = array(
      TaxonomyTerm::getUniqueName('tags'),
    );
    static::$expectedValueMultiple = static::$valueMultiple[0];
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
    $this->assertEquals(static::$expectedValueZero, $values, "Values filled into " . static::$field_name . " are not correct.");
    static::$fields[static::$field_name] = static::$expectedValueZero;

    list($success, $nodeObject, $msg) = $testForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $nodeObject->checkValues(static::$fields);
    $this->assertTrue($success, $msg);

    static::$fields[static::$field_name] = 'abc';
    list($success, $msg) = $nodeObject->checkValues(static::$fields);
    $this->assertFalse($success, $msg);
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

      list($success, $values, $msg) = $testForm->{static::$fillRandomFunctionName}();
      $this->assertTrue($success, $msg);
      static::$fields[static::$field_name] = $values;

      list($success, $nodeObject, $msg) = $testForm->submit();
      $this->assertTrue($success, $msg);

      list($success, $msg) = $nodeObject->checkValues(static::$fields);
      $this->assertTrue($success, $msg);
    }
  }

  /**
   * Log out and delete the entities created in this test.
   */
  public static function tearDownAfterClass() {
    static::$userObject->logout();
    Utils::deleteCreatedEntities();
  }
}