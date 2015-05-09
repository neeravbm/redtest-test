<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 1/26/15
 * Time: 9:18 AM
 */

namespace tests\RedTest\tests\article\crud;

use RedTest\core\entities\User;
use RedTest\core\fields\Field;
use RedTest\core\fields\Text;
use RedTest\core\Utils;
use RedTest\entities\Node\Article;
use RedTest\forms\entities\Node\ArticleForm;

/**
 * Drupal root directory.
 */
define('DRUPAL_ROOT', getcwd());
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
drupal_override_server_variables();
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

class AuthenticatedUserTest extends \PHPUnit_Framework_TestCase {

  private static $userObject;

  protected $backupGlobalsBlacklist = array('user', 'entities');

  public static function setupBeforeClass() {
    list($success, $userObject, $msg) = User::createDefault();
    self::assertTrue($success, $msg);

    self::$userObject = User::loginProgrammatically(
      $userObject->getUidValues()
    );
  }

  public function testArticleCreation() {
    /**
     * @var Article $articleObject
     */
    $articleForm = new ArticleForm();

    list($success, $fields, $msg) = $articleForm->fillDefaultValues();
    $this->assertTrue($success, $msg);

    list($success, $articleObject, $msg) = $articleForm->submit();
    $this->assertTrue($success, $msg);

    list($success, $msg) = $articleObject->checkValues($fields);
    $this->assertTrue($success, $msg);
  }

  public static function tearDownAfterClass() {
    self::$userObject->logout();
    Utils::deleteCreatedEntities();
  }
}