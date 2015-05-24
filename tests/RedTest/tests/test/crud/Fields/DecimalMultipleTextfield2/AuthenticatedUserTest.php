<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/20/15
 * Time: 9:34 AM
 */

namespace RedTest\tests\test\crud\Fields\DecimalMultipleTextfield2;


use RedTest\tests\test\crud\Fields\Templates\AuthenticatedUser;

class AuthenticatedUserTest extends AuthenticatedUser {

  protected static $field_name = 'field_decimal_multiple_text_2';

  protected static $expectedValueEmpty = '';

  protected static $valueOne = 35.78;

  protected static $expectedValueOne = 35.78;

  protected static $valueMultiple = array(0.56, -9.67, 2.3);

  protected static $expectedValueMultiple = array(0.56, -9.67, 2.3);

  protected static $valueZero = array(2, 0);

  protected static $expectedValueZero = array(2, 0);
}