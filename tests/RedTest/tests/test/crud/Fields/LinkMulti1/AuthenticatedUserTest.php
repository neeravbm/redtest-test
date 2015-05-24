<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 5/20/15
 * Time: 9:34 AM
 */

namespace RedTest\tests\test\crud\Fields\LinkMulti1;


use RedTest\tests\test\crud\Fields\Templates\AuthenticatedUser;

class AuthenticatedUserTest extends AuthenticatedUser {

  protected static $field_name = 'field_link_multi_1';

  protected static $expectedValueEmpty = '';

  protected static $valueOne = 35.78;

  protected static $expectedValueOne = 35.78;

  protected static $valueMultiple = array(
    "http://redcrackle.com",
    array('url' => 'http://www.google.com'),
    array(
      'title' => 'Times of India',
      'url' => 'http://timesofindia.indiatimes.com'
    )
  );

  protected static $expectedValueMultiple = array(
    "http://redcrackle.com",
    'http://www.google.com',
    array(
      'title' => 'Times of India',
      'url' => 'http://timesofindia.indiatimes.com'
    )
  );

  protected static $valueZero = array("http://www.google.com", 0);

  protected static $expectedValueZero = "http://www.google.com";
}