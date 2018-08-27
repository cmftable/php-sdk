<?php

namespace Comfortable\Traits;

trait FieldsTrait {
  protected $fields;
  
  public function fields($fields = null) {
    $this->fields = $fields;
    return $this;
  }

  public function getFields() {
    return $this->fields;
  }
}