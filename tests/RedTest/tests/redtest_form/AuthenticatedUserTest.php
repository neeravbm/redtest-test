<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 2/19/15
 * Time: 5:00 PM
 */

namespace tests\RedTest\tests\redtest_form;

use RedTest\core\entities\User;
use RedTest\core\RedTest_Framework_TestCase;
use RedTest\core\Utils;
use RedTest\entities\Node\Test;
use RedTest\forms\entities\Node\TestForm;
use RedTest\core\forms\Form;


/**
 * Class AuthenticatedUserTest
 *
 * @package tests\RedTest\tests\test\crud\Fields\BooleanCheckboxes
 */
class AuthenticatedUserTest extends RedTest_Framework_TestCase {

  /**
   * @var User
   */
  private static $userObject;

  /**
   * Create an authenticated user and log in.
   */
  public static function setupBeforeClass() {
    list($success, $userObject, $msg) = User::createRandom();
    self::assertTrue($success, $msg);

    list($success, self::$userObject, $msg) = User::loginProgrammatically($userObject->getId());
    self::assertTrue($success, $msg);
  }

  /**
   * Submit a RedTest form with Field 1 set.
   */
  public function testIndividualFieldSubmission() {
    $form = new Form('redtest_form');

    $fields = array();

    list($success, $values, $msg) = $form->fillRedtestTextfield1RandomValues();
    $this->assertTrue($success, $msg);
    $fields['redtext_textfield_1'] = $values;

    list($success, $values, $msg) = $form->fillRedtestTextfield2RandomValues();
    $this->assertTrue($success, $msg);
    $fields['redtext_textfield_2'] = $values;

    list($success, $msg) = $form->pressButton();
    $this->assertTrue($success, $msg);

    $this->assertEquals(
      $fields['redtext_textfield_1'],
      variable_get('redtest_textfield_1', 'Hi'),
      "Textfield 1's value is not set correctly."
    );
    $this->assertEquals(
      $fields['redtext_textfield_2'],
      variable_get('redtest_textfield_2', 'Haha'),
      "Textfield 2's value is not set correctly."
    );
  }
}