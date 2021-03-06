<?php

define("TAG_SET", "SET");
define("TAG_DEL", "DEL");
define("TAG_RES", "RES");

class TagWork {
	private $basetags;
	private $tagkeys;
	public function __construct() {
		$src = file(PHPATH . '/tags.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		$this->tagkeys = array();
		$tkfile = file(PHPATH . "/tagkeys.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach($tkfile as $tk) {
			$tk = explode(" ", $tk);
			$this->tagkeys[$tk[1]] = $tk[0];
		}

		$this->basetags = array();
		foreach ($src as $tg) {
			$basename = Tag::parseBaseName($tg);
			if (array_key_exists($basename, $this->basetags)) 
				throw new Exception("One basename used multiple times");

			$this->basetags[$basename] = $tg;
		}
	}

	public function getTags($listused = array()) {
		$ret = array();
		$used = array();
		foreach($listused as $tag) {
			$base = Tag::parseBaseName($tag);
			$selected = Tag::parseSelectedName($tag);

			$used[$base] = $selected;
		}

		foreach($this->basetags as $base => $tagline) {
			$tagobj = new Tag($tagline);
			$ret[] = $tagobj;
			if (array_key_exists($tagobj->getBaseName(), $used)) {
				$tagobj->setSelected($used[$tagobj->getBaseName()]);
			}

			if (array_key_exists($tagobj->getBaseName(), $this->tagkeys))
				$tagobj->setKey($this->tagkeys[$tagobj->getBaseName()]);
		}

		return $ret;
	}

	public function getTag(string $tag) {
		$bs = Tag::parseBaseName($tag);
		$sel = Tag::parseSelectedName($tag);

		if (!array_key_exists($bs, $this->basetags))
			throw new Exception("Non-existent tag");

		$ret = new Tag($this->basetags[$bs]);
		if ($sel !== null)
			$ret->setSelected($sel);

		if (array_key_exists($bs, $this->tagkeys))
			$ret->setKey($this->tagkeys[$bs]);

		return $ret;
	}

	public function getDirectorySubtagSet(ImageListCsv $imgs, string $tag) {
		$set = array();
		for ($i = 0; $i < $imgs->count(); $i++) {
			$img = $imgs->getImage($i);
			$tagset = new ImageTagSet($img, $this);

			$tg = $tagset->getTag($tag);
			if ($tg === null) continue;

			$set[$tg->getNotation()] = $tg;
		}

		return $set;
	}
}

class Tag {
	private $checked, $tag, $selected;
	private $subs;
	private $hidden;
	private $asckey;

	public function __construct(string $tagline, bool $checked = false) {
		if ($tagline[0] == "!") {
			$this->hidden = true;
			$tagline = substr($tagline, 1);
		} else {
			$this->hidden = false;
		}

		$this->checked = $checked;

		$tg = explode(":", $tagline);
		$this->tag = $tg[0];

		$this->subs = array();
		if (count($tg) == 2) {
			$subs = explode(",", $tg[1]);

			foreach($subs as $subtag) {
				$cur = new SubTag($this, $subtag);
				$this->subs[] = $cur;
			}
		}
		$this->asckey = null;
	}

	public function __toString() {
		return $this->getNotation();
	}

	public function setSelected($selected) {
		$this->checked = true;
		if ($selected === null) {
			$this->selected = null;
			return;
		}

		foreach ($this->subs as $sub) {
			if ($sub->getName() === $selected)
				$this->selected = $sub;
		}

		if ($this->selected === null) {
			throw new Exception("Not my subtag :(");
		}
	}

	public function getBaseName(): string {
		return $this->tag;
	}

	public function getSubtags(): array {
		return $this->subs;
	}

	public function isChecked(): bool {
		return $this->checked;
	}

	public function isHidden(): bool {
		return $this->hidden;
	}

	public function haveSubs() {
		return count($this->subs) > 0;
	}

	public function getSelectedSubtag() {
		return $this->selected;
	}

	public function getNotation(): string {
		$sel = $this->getSelectedSubtag();
		if ($sel !== null)
			return $sel->getNotation();
		else 
			return $this->getBaseName();
	}

	public function setKey($key) {
		$this->asckey = $key;
	}

	public function getKey() {
		return $this->asckey;
	}

	/* STATIC */
	
	public static function parseBaseName(string $tag) {
		$tg = explode(":", $tag);
		return $tg[0][0] == "!" ? substr($tg[0], 1) : $tg[0];
	}

	public static function parseSelectedName(string $tag) {
		$tg = explode(":", $tag);
		if (count($tg) == 2) return $tg[1];

		return null;
	}
}

class SubTag {
	private $parent, $subtag;

	public function __construct(Tag $parent, string $subtag) {
		$this->parent = $parent;
		$this->subtag = $subtag;
	}

	public function getName(): string {
		return $this->subtag;
	}

	public function getNotation(): string {
		return $this->parent->getBaseName() . ":" . $this->subtag;
	}
}

class ImageTagSet {
	private $tagset;
	private $tw;
	private $img;
	public function __construct(ImageCsvLine $img, TagWork $tw) {
		$this->tw = $tw;
		$this->img = $img;
		$this->tagset = Array();
		foreach($img->getTags() as $strtag) {
			$tg = $tw->getTag($strtag);
			$this->tagset[$tg->getBaseName()] = $tg;
		}
	}

	// tag command is in form CMD:tag:subtag (CMD is one of the defines on the beginning of this file)
	public function tagCommand(string $tagcmd) {
		$tg = explode(":", $tagcmd, 2);
		if ($tg[0] == TAG_SET) 
			$this->setTag($tg[1]);
		else if ($tg[0] == TAG_DEL)
			$this->removeTag($tg[1]);
		else if ($tg[0] == TAG_RES)
			$this->emptySubtag($tg[1]);
		else
			throw new Exception("Unknown tag command ${tg[0]}");
	}

	public function getTag(string $tag) {
		$tg = Tag::parseBaseName($tag);

		if (!array_key_exists($tg, $this->tagset))
			return null;

		return $this->tagset[$tg];
	}

	public function getTags(): array {
		$ret = array();

		foreach($this->tagset as $tag) {
			$ret[$tag->getBaseName()] = $tag;
		}

		return $ret;
	}

	/** Add or change tag to a tagset. $tag can be in base form or with selection
	 *   - in case a tag is not set, just set it and set subtag if present
	 *   - if a subtag is not present and tag is set, just leave the selection
	 * */
	public function setTag(string $tag) {
		$newtg = $this->tw->getTag($tag);

		if (array_key_exists($newtg->getBaseName(), $this->tagset)) {
			$curtg = $this->tagset[$newtg->getBaseName()];
			$subt = $newtg->getSelectedSubtag();

			if ($subt !== null)
				$curtg->setSelected($subt->getName());
		} else {
			$this->tagset[$newtg->getBaseName()] = $newtg;
		}
	}

	public function removeTag(string $tag) {
		$rmtag = Tag::parseBaseName($tag);

		if (array_key_exists($rmtag, $this->tagset)) {
			unset($this->tagset[$rmtag]);
			return;
		}

		throw new Exception("Cannot delete non-existent tag");
	}

	public function emptySubtag(string $tag) {
		$emtag = Tag::parseBaseName($tag);

		if (array_key_exists($emtag, $this->tagset)) {
			$this->tagset[$emtag]->setSelected(null);
			return;
		}

		throw new Exception("Cannot reset non-existent tag");
	}

	public function hasBaseTag(string $tag): bool {
		$tg = Tag::parseBaseName($tag);

		return array_key_exists($tg, $this->tagset);
	}

	public function hasFullTag(string $tag): bool {
		if (!$this->hasBaseTag($tag)) 
			return false;

		$sb = Tag::parseSelectedName($tag);
		if ($sb === null) 
			return true; // 

		$tg = Tag::parseBaseName($tag);

		$sel = $this->tagset[$tg]->getSelectedSubtag();
		if ($sel === null) 
			return false;

		return $sel->getName() == $sb;
	}

	public function saveTags() {
		$tags = array_map(function($itm) { return $itm->getNotation();}, $this->tagset);
		$this->img->setTags($tags);
		$this->img->save();
	}
}
