<?php
namespace Comfortable\Traits;

trait FilterTrait {
  protected $filter;

  public function filter(\Comfortable\Filter $filter) {
    $this->filter = $filter->getFilter();
    return $this;
  }

  public function getFilter() {
    return $this->filter;
  }
}