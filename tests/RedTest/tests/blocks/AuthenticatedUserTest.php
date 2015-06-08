<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/24/15
 * Time: 2:52 PM
 */

namespace RedTest\tests\blocks;

use RedTest\core\Block;
use RedTest\core\entities\User;
use RedTest\core\RedTest_Framework_TestCase;


class AuthenticatedUserTest extends RedTest_Framework_TestCase {

  public static function setUpBeforeClass() {
    list($success, $userObject, $msg) = User::createRandom();
    self::assertTrue($success, $msg);

    list($success, $userObject, $msg) = User::loginProgrammatically(
      $userObject->getId()
    );
    self::assertTrue($success, $msg);
  }

  public function testBlock1() {
    $block = new Block('block_1');
    $this->assertTrue(
      $block->isPresent('a'),
      "Block 1 is not present on /a path."
    );
    $this->assertTrue(
      $block->isPresent('<front>'),
      "Block 1 not present on the homepage."
    );
  }

  public function testBlock2() {
    $block = new Block('block_2');
    $this->assertFalse(
      $block->isPresent('a'),
      "Block 2 is present on /a path."
    );
    $this->assertTrue(
      $block->isPresent('<front>'),
      "Block 2 is not present on the homepage."
    );
  }
}