<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 2/19/15
 * Time: 5:00 PM
 */

namespace tests\RedTest\tests\redtest_form;

use RedTest\core\entities\User;
use RedTest\core\Utils;
use RedTest\entities\Node\Test;
use RedTest\forms\entities\Node\TestForm;
use RedTest\core\forms\Form;

/**
 * Drupal root directory.
 */
if (!defined('DRUPAL_ROOT')) {
  define('DRUPAL_ROOT', getcwd());
}
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
// We need to provide a non-empty SERVER_SOFTWARE so that execution doesn't get
// treated as command-line execution by drupal_is_cli() function. If it is
// treated as command-line execution, then drupal_session_start() doesn't invoke
// session_start(). As a result, session_destroy() in User::logout() function
// throws an error. Although this does not affect RedTest execution or even
// session handling, it's better to not let Drupal throw this error in the first
// place.
if (empty($_SERVER['SERVER_SOFTWARE'])) {
  drupal_override_server_variables(array('SERVER_SOFTWARE' => 'RedTest'));
}
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
   * @var User
   */
  private static $userObject;

  /**
   * Create an authenticated user and log in.
   */
  public static function setupBeforeClass() {
    list($success, $userObject, $msg) = User::createDefault();
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

    list($success, $values, $msg) = $form->fillDefaultRedtestTextfield1Values();
    $this->assertTrue($success, $msg);
    $fields['redtext_textfield_1'] = $values;

    list($success, $values, $msg) = $form->fillDefaultRedtestTextfield2Values();
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

  /**
   * Log out and delete the entities created in this test.
   */
  public static function tearDownAfterClass() {
    self::$userObject->logout();
    Utils::deleteCreatedEntities();
  }
}