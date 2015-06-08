<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/23/15
 * Time: 9:31 AM
 */

namespace RedTest\tests\views;

use RedTest\core\entities\User;
use RedTest\entities\Node\Test;
use RedTest\core\Utils;
use RedTest\core\View;

/**
 * Drupal root directory.
 */
if (!defined('DRUPAL_ROOT')) {
  define('DRUPAL_ROOT', getcwd());
}
require_once DRUPAL_ROOT . '/includes/bootstrap.inc';
if (empty($_SERVER['SERVER_SOFTWARE'])) {
  drupal_override_server_variables(array('SERVER_SOFTWARE' => 'RedTest'));
}
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);


class ListOfTestNodes1Test extends \PHPUnit_Framework_TestCase {

  /**
   * @var array
   */
  protected $backupGlobalsBlacklist = array('user', 'entities', 'language', 'language_url', 'language_content');

  private static $testObjects;

  public static function setUpBeforeClass() {
    list($success, $userObject, $msg) = User::loginProgrammatically(1);
    self::assertTrue($success, $msg);

    list($success, self::$testObjects, $msg) = Test::createRandom(3);
    self::assertTrue($success, $msg);
    $userObject->logout();
  }

  public function testAuthenticatedUser() {
    list($success, $userObject, $msg) = User::createRandom();
    $this->assertTrue($success, $msg);

    list($success, $userObject, $msg) = User::loginProgrammatically($userObject->getId());
    $this->assertTrue($success, $msg);

    /*Utils::sort(self::$testObjects, "nid DESC");

    $view = new View('list_of_test_nodes');
    //print_r($view->getUrl());
    print_r($view->hasAccess());
    $results = $view->execute(array(807), array('type' => array('page', 'test')));
    //print_r($view->getUrl());
    print_r($results);
    $this->assertTrue($view->hasValues(array(array('nid' => 142, 'node_title' => 'Trial by Author'), array('nid' => 144)), FALSE, TRUE), "View values do not match.");
    //print_r($view->hasAccess());
    $userObject->logout();*/

  }

  public static function tearDownAfterClass() {
    Utils::deleteCreatedEntities();
  }

}