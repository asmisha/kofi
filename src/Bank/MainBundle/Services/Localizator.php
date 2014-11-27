<?php
/**
 * User: asmisha
 * Date: 27.11.14
 * Time: 23:35
 */

namespace Bank\MainBundle\Services;


class Localizator {
	private $locales;

	function __construct($locales)
	{
		$this->locales = $locales;
	}


	/**
	 * @param $object
	 * @param $fields
	 * @return mixed
	 */
	public function setLocales($object, $fields){
		$locales = $this->locales;
		$empty = array_combine(
			$locales,
			array_fill(0, count($locales), '')
		);

		if(!is_array($fields))
			$fields = array($fields);

		foreach($fields as $f){
			$set = "set$f";
			$get = "get$f";
			@$object->$set(is_array($object->$get()) ? array_merge($empty, $object->$get()) : $empty);
		}

		return $object;
	}
} 