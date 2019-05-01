<?php

class QueryEngine {
	private $query;
	private $args;
	private $func;
	private $order;

	public function __construct($query) {
		$this->query = $query;
		$this->args = array();
		$this->order = array();

		$newq = str_ireplace( array("AND", "OR", "NOT"), array("&&", "||", "!"), $query);
		$newq = preg_replace_callback("/([a-z]+)/", array($this, "prrcmatch"), $newq);

		$this->args = array_unique($this->args);
		for ($i = 0; $i < count($this->args); $i++) {
			$this->order[$this->args[$i]] = $i;
		}

		$argnames = array_map(function($x) { return '$'.$x ; }, $this->args);
		$argnames = implode(",", $argnames);
		$this->func = create_function($argnames, "return ${newq};");
	}

	private function prrcmatch($mtch) {
		$this->args[] = $mtch[1];
		return "\$" . $mtch[1];
	}

	public function match($taglist) {
		$args = array_fill(0, count($this->args), false);

		foreach($taglist as $tag) {
			if (array_key_exists($tag, $this->order))
				$args[$this->order[$tag]] = true;
		}

		$ret = call_user_func_array($this->func, $args);
		return $ret;
	}
}
