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

  protected $backupGlobalsBlacklist = array('user');

  public static function setupBeforeClass() {
    list($success, $userObject, $msg) = User::createDefault();
    self::assertTrue(
      $success,
      "Authenticated user could not be created: " . $msg
    );

    self::$userObject = User::loginProgrammatically(
      $userObject->getEntity()->name
    );
  }

  public function testArticleCreation() {
    /**
     * @var Article $articleObject
     */
    list($success, $articleObject, $msg) = Article::createDefault(1);
    $this->assertTrue(
      $success,
      "Authenticated user is not able to create an article: " . $msg
    );
  }

  public static function tearDownAfterClass() {
    self::$userObject->logout();
    Utils::deleteCreatedEntities();
  }
}