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
   *   Node id if an existing node form needs to be loaded. If a new node form
   *   is to be created, then keep it empty.
   * @param array $options
   *   Options array.
   */
  public function __construct($nid = NULL, $options = array()) {
    parent::__construct($nid, $options);
  }
}