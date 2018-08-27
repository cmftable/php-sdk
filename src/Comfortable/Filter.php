<?php

namespace Comfortable;

class Filter {
  protected $filter = [];

  /** construct */
  public function __construct() { }

  /**
   * add a logical 'and' filter
   *
   * @param string $property
   * @param string $operator
   * @param mixed $value
   * @param string $context
   * @param string $contentType
   * @return void
   */
  public function addAnd($property, $operator, $value, $context = 'fields', $contentType = '*') {
    $filter = [
      "$contentType.$context.$property" => [
        "$operator" => $value
      ]
    ];

    if (sizeof($this->filter) === 0) {
      array_push($this->filter, $filter);
    } else if (sizeof($this->filter) > 0) {
      array_push($this->filter, [ "and" => $filter ]);
    }

    return $this;
  }

  /**
   * add a logical 'or' filter
   *
   * @param string $property
   * @param string $operator
   * @param mixed $value
   * @param string $context
   * @param string $contentType
   * @return void
   */
  public function addOr($property, $operator, $value, $context = 'fields', $contentType = '*') {
    $filter = [
      "$contentType.$context.$property" => [
        "$operator" => $value
      ]
    ];

    if (sizeof($this->filter) === 0) {
      array_push($this->filter, $filter);
    } else if (sizeof($this->filter) > 0) {
      array_push($this->filter, [ "or" => $filter ]);
    }

    return $this;
  }

  /**
   * return current filter
   *
   * @return void
   */
  public function getFilter() {
    return $this->filter;
  }
}
?>