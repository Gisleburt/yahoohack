<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Daniel
 * Date: 27/04/13
 * Time: 23:05
 * To change this template use File | Settings | File Templates.
 */

namespace Application\Model;


class SearchResult {

	public $name;
	public $description;
	public $latitude;
	public $longitude;
	public $thumbnail;
	public $url;

	public function __construct(array $values = array()) {
		$this->setValues($values);
	}

	public function setValues(array $values) {
		foreach($values as $key => $value) {
			$this->$key = $value;
		}
	}

}