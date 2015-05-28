<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/20/15
 * Time: 9:34 AM
 */

namespace RedTest\tests\test\crud\Fields\DecimalSingleTextfield1;


use RedTest\tests\test\crud\Fields\Templates\AuthenticatedUser;

class AuthenticatedUserTest extends AuthenticatedUser {

  protected static $field_name = 'field_decimal_single_textfield_1';

  protected static $expectedValueEmpty = '';

  protected static $valueOne = 35.78;

  protected static $expectedValueOne = 35.78;

  protected static $valueMultiple = array(-0.56);

  protected static $expectedValueMultiple = -0.56;

  protected static $valueZero = 0;

  protected static $expectedValueZero = 0;
}