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
    $userObject = User::createRandom()->verify(get_class());

    self::$userObject = User::loginProgrammatically($userObject->getId())
      ->verify(get_class());
  }

  /**
   * Submit a RedTest form with Field 1 set.
   */
  public function testIndividualFieldSubmission() {
    $form = new Form('redtest_form');
    $form->verify($this);

    $fields = array();

    $values = $form->fillRedtestTextfield1RandomValues()->verify($this);
    $fields['redtext_textfield_1'] = $values;

    $values = $form->fillRedtestTextfield2RandomValues()->verify($this);
    $fields['redtext_textfield_2'] = $values;

    $form->pressButton()->verify($this);

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