<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/20/15
 * Time: 9:34 AM
 */

namespace RedTest\tests\test\crud\Fields\BooleanSingleCheckbox2;


use RedTest\tests\test\crud\Fields\Templates\AuthenticatedUser;

class AuthenticatedUserTest extends AuthenticatedUser {

  protected static $field_name = 'field_boolean_single_checkbox_2';

  protected static $expectedValueEmpty = '';

  protected static $valueOne = 1;

  protected static $expectedValueOne = 1;

  protected static $valueMultiple = array(1, 0);

  protected static $expectedValueMultiple = array(1, 0);

  protected static $valueZero = array(0);

  protected static $expectedValueZero = 0;
}