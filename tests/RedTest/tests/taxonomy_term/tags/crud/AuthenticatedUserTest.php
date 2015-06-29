<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 2/19/15
 * Time: 5:00 PM
 */

namespace RedTest\tests\taxonomy_term\crud;

use RedTest\core\entities\User;
use RedTest\core\RedTest_Framework_TestCase;
use RedTest\core\Utils;
use RedTest\core\Menu;
use RedTest\entities\TaxonomyTerm\Tags;


class AuthenticatedUserTest extends RedTest_Framework_TestCase {

  /**
   * @var User
   */
  private static $userObject;

  public static function setupBeforeClass() {
    $userObject = User::createRandom()->verify(get_class());

    User::logout();

    self::$userObject = User::login(
      $userObject->getNameValues(),
      $userObject->getPasswordValues()
    )->verify(get_class());
  }

  public function testTagsCreationAccess() {
    $this->assertEquals(
      'drupal_get_form',
      Menu::getPageCallback('admin/structure/taxonomy/tags/add'),
      "Page callback to add a Tags taxonomy term is incorrect."
    );

    $pageArguments = Menu::getPageArguments(
      'admin/structure/taxonomy/tags/add'
    );
    $pageArgument = array_shift($pageArguments);
    $this->assertEquals(
      'taxonomy_form_term',
      $pageArgument,
      "Page arguments to add a Tags taxonomy term are incorrect."
    );

    $this->assertFalse(
      Menu::hasAccess('admin/structure/taxonomy/tags/add'),
      "Authenticated user has access to create a Tags taxonomy term."
    );
  }

  public function testTagsUpdateAccess() {
    User::logout();
    $superuserObject = User::loginProgrammatically(1)->verify($this);
    $tagsObject = Tags::createRandom()->verify($this);
    $superuserObject->logout();

    self::$userObject = User::login(
      self::$userObject->getNameValues(),
      self::$userObject->getPasswordValues()
    )->verify($this);

    $tid = $tagsObject->getId();
    $path = "taxonomy/term/$tid/edit";

    $this->assertEquals(
      'drupal_get_form',
      Menu::getPageCallback($path),
      "Page callback to edit a Tags taxonomy term is incorrect."
    );

    $pageArguments = Menu::getPageArguments($path);
    $pageArgument = array_shift($pageArguments);
    $this->assertEquals(
      'taxonomy_form_term',
      $pageArgument,
      "Page arguments to edit a Tags taxonomy term are incorrect."
    );

    $this->assertFalse(
      Menu::hasAccess($path),
      "Authenticated user has access to edit a Tags taxonomy term."
    );
  }

  public static function tearDownAfterClass() {
    self::$userObject->logout();
    Utils::deleteCreatedEntities();
  }
}