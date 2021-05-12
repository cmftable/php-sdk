<?php

namespace Comfortable;

class Sorting {
  
  /**
   * @var array
   */
  private $sorting = [];

  /** construct */
  public function __construct() { }

  /**
   * @param string $property
   * @param string $direction
   * @param string $context
   * @return void
   */
  public function add($property, $direction = 'ASC', $context = 'fields') {
    $direction = (strtolower($direction) === "asc" || strtolower($direction) === "desc") ? strtolower($direction) : 'ASC';
    $this->sorting["{$context}.{$property}"] = $direction;

    return $this;
  }

  /**
   * @return void
   */
  public function getSorting(){
    return $this->sorting;
  }
}
?>