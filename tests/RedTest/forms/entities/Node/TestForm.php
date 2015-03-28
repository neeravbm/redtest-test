<?php
/**
 * Created by PhpStorm.
 * User: neeravm
 * Date: 2/19/15
 * Time: 4:59 PM
 */

namespace RedTest\forms\entities\Node;

use RedTest\core\forms\entities\Node\NodeForm;

class TestForm extends NodeForm {

  /**
   * Default constructor. This is needed so that other classes can create
   * TestForm. If we don't have this class, then other classes can't call
   * NodeForm directly because NodeForm's constructor is a protected function.
   *
   * @param null|int $nid
   */
  public function __construct($nid = NULL) {
    parent::__construct($nid);
  }
}