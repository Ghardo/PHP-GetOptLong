<?php
namespace Cli;

class GetOptLong {
	const ARGUMENT_NONE = 'none';
	const ARGUMENT_OPTIONAL = 'optional';
	const ARGUMENT_REQUIRED = 'required';

	const INDEX_LONG = 'long';
	const INDEX_SHORT = 'short';
	const INDEX_REQUIREMENT = 'requirement';

	protected $_arguments = array();
	protected $_mapping = array();
	protected $_values = null;

	public function __construct($args = null) {
		if ($args !== null)
			$this->args($args);
	}

	public function args($args) {
		foreach($args as $arg) {
			call_user_func_array(
				array($this, 'arg'),
				$arg
			);
		}
	}

	public function arg($long, $short, $requirement) {
		if ($long === null && $short === null) 
			throw new \Exception('long or short param required');

		if ($requirement === null) 
			throw new \Exception('requirement is required');

		if ($long !== null) 
			$key = $long;
		else if ($short !== null)
			$key = $short;

		$this->_arguments[$key] = array(
			self::INDEX_LONG => ($long !== null ? trim($long, '-') : null),
			self::INDEX_SHORT => ($short !== null ? trim($short, '-') : null),
			self::INDEX_REQUIREMENT => $requirement 
		);

		if ($short !== null && $long !== null)
			$this->_mapping[$short] = $long;
	}

	public function has($name) {
		return $this->exists($name);
	}

	public function exists($name) {
		return $this->raw($name) !== null;
	}

	public function __get($name) {
		if ($this->_values === null)
			$this->_parse();

		$value = null;
		$key = $this->_mapping($name);

		if (array_key_exists($key, $this->_values))
			$value = $this->_values[$key];

		if ($value === false)
			return null;

		return $value;
	}

	public function raw($name) {
		if ($this->_values === null)
			$this->_parse();

		$value = null;
		$key = $this->_mapping($name);

		if (array_key_exists($key, $this->_values))
			$value = $this->_values[$key];

		return $value;
	}

	protected function _mapping($key) {
		if (array_key_exists($key, $this->_mapping) && array_key_exists($this->_mapping[$key], $this->_values))
			return $this->_mapping[$key];

		$mapping = array_flip($this->_mapping);
		if (array_key_exists($key, $mapping) && array_key_exists($mapping[$key], $this->_values))
			return $mapping[$key];

		return $key;
	}

	protected function _parse() {
		$shortargs = '';
		$longargs = array();

		foreach ($this->_arguments as $key => $argument) {

			if ($argument[self::INDEX_SHORT] !== null)
				$shortargs .= $argument[self::INDEX_SHORT];

			if ($argument[self::INDEX_LONG] !== null)
				$longargs[$key] = $argument[self::INDEX_LONG];

			switch ($argument[self::INDEX_REQUIREMENT]) {
				case self::ARGUMENT_NONE:
					// do nothing here
					break;
				case self::ARGUMENT_OPTIONAL:
					if ($argument[self::INDEX_SHORT] !== null)
						$shortargs .= '::';

					if ($argument[self::INDEX_LONG] !== null)
						$longargs[$key] .= '::';
					break;
				case self::ARGUMENT_REQUIRED:
					if ($argument[self::INDEX_SHORT] !== null)
						$shortargs .= ':';

					if ($argument[self::INDEX_LONG] !== null)
						$longargs[$key] .= ':';
					break;
				
				default:
					throw new \Exception('unknown requirement');
					break;
			}
		}

		$this->_values = getopt($shortargs, $longargs);
	}
}
