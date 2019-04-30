<?php

class TagWork {
	private $basetags;
	public function __construct() {
		$this->basetags = file(PHPATH . '/tags.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	}

	public function getTags($listused = array()) {
		$ret = array();
		$used = array();
		foreach($listused as $tag) {
			$ret[] = new Tag($tag, true);
			$used[$tag] = true;
		}

		foreach($this->basetags as $tag) {
			if (!array_key_exists($tag, $used)) {
				$ret[] = new Tag($tag);
			}
		}

		return $ret;
	}
}

class Tag {
	private $tag, $checked;
	public function __construct($tag, $checked = false) {
		$this->tag = $tag;
		$this->checked = $checked;
	}

	public function getBaseName() {
		$tg = explode(":", $this->tag);
		return $tg[0];
	}

	public function isChecked() {
		return $this->checked;
	}
}
