<?php

class ImageCsvLine {
	private $row, $ilc, $idx;

	private $delondst;
	public function __construct(&$row, $idx, $ilc) {
		$this->row = &$row;
		$this->ilc = $ilc;
		$this->idx = $idx;

		$this->delondst = array();
	}

	public function __destruct() {
/*		foreach($this->delondst as $dir)
	$this->deleteDirectory($dir);*/
	}

	private function deleteDirectory($dir) {
		if (!file_exists($dir)) {
			return true;
		}

		if (!is_dir($dir)) {
			return unlink($dir);
		}

		foreach (scandir($dir) as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}

			if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
				return false;
			}

		}

		return rmdir($dir);
	}

	public function getID() {
		return $this->ilc->getPath() . "/" . $this->idx;
	}

	public function getThumbPath() {
		return $this->ilc->getFullPath() . "/" . $this->getThumbFile();
	}

	public function getThumbFile() {
		return $this->row["thumb"];
	}

	public function isRaw() {
		$ext = explode(".", $this->getOriginalFile());
		$ext = $ext[count($ext) - 1];

		return (strtolower($ext) == "rw2");
	}

	public function isJpeg() {
		$ext = explode(".", $this->getOriginalFile());
		$ext = $ext[count($ext) - 1];

		return (strtolower($ext) == "jpg" || strtolower($ext) == "jpeg");
	}


	public function genFullres() {
		if ($this->isJpeg()) 
			return $this->getRawPath();

		$dr = tempnam(sys_get_temp_dir(), 'foootoo'); // good 
		if (file_exists($dr)) unlink($dr);
		mkdir($dr);
		$this->delondst[] = $dr;

		$fl = $this->getRawPath();
		exec ("exiv2 -ep2 -l ${dr} ${fl}");

		// result sould be ORIGINALNAME-preview2.jpg

		$res = $dr . "/" . explode(".", $this->getOriginalFile())[0] . "-preview2.jpg";

		return $res;
	}

	public function getPath() {
		return $this->ilc->getPath();
	}

	public function getOriginalFile() {
		return $this->row["original"];
	}

	public function getRawPath() {
		return RAWPATH . $this->ilc->getPath() . "/" . $this->getOriginalFile();
	}

	public function getDesc() {
		return $this->row["desc"];
	}

	public function setDesc($desc) {
		$this->row["desc"] = $desc;
	}

	public function getTags() {
		return array_filter(explode(",", $this->row["tags"]));
	}

	public function getNext() {
		return ($this->ilc->count() > $this->idx + 1) ? $this->ilc->getImage($this->idx + 1) : null;
	}

	public function getPrev() {
		return ($this->idx > 0) ? $this->ilc->getImage($this->idx - 1) : null;
	}

	public function setTags($tags) {
		$this->row["tags"] = implode(",", $tags);
	}

	public function save() {
		$this->ilc->save();
	}
}

class ImageListCsv {
	private $csv;
	private $path;
	public function __construct($path) {
		$filepath = BASEPATH . "/" . $path . "/.tag-index";
		if (!file_exists($filepath))  
			throw new Exception("No exists " . $path);

		$this->path = $path;
		$this->csv = new ParseCsv\Csv();
		$this->csv->heading = false;
		$this->csv->delimiter=";";
		$this->csv->input_encoding = $this->csv->output_encoding = "utf-8";
		$this->csv->linefeed = "\n";
		$this->csv->fields = array("thumb", "original", "desc", "tags");
		$this->csv->parse($filepath);

		for ($i = 0; $i < $this->count(); $i++) {
			if ($this->csv->data[$i] === null) {
				echo "El bug qui!!!<br /><br />\n\n";
				print_r($this->csv->data);
			}
			if (!array_key_exists("desc", $this->csv->data[$i]))
				$this->csv->data[$i]["desc"] = "";
			if (!array_key_exists("tags", $this->csv->data[$i]))
				$this->csv->data[$i]["tags"] = "";
		}
	}

	public function count() {
		return count($this->csv->data);
	}

	public function untaggedCount() {
		$cnt = 0;
		for ($i = 0; $i < $this->count(); $i++) {
			$c = count($this->getImage($i)->getTags());
			if ($c == 0) $cnt++;
		}

		return $cnt;
	}

	public function getImage($idx) {
		return new ImageCsvLine($this->csv->data[$idx], $idx, $this);
	}

	public function getPath() {
		return $this->path;
	}

	public function getFullPath() {
		return BASEPATH . "/" . $this->path;
	}

	public function save() {
		$this->csv->save();
	}


}

class ImageFolderList {
	private $lst;
	public function __construct() {
		$dir = scandir(BASEPATH);

		foreach($dir as $directory) {
			if ($directory == "." || $directory == "..") continue;

			try {
				$this->lst[$directory] = new ImageListCsv($directory);
			}
			catch (Exception $e) {
				// es bien porque puede estar un directria sin file
			}
		}
	}

	public function getIterator() {
		$ao = new ArrayObject($this->lst);
		return $ao->getIterator();
	}

	public function getImgList($id) {
		$nm = explode("/", $id);
		if (count($nm) != 1 && count($nm) != 2)
			throw new Exception("Bad ID, ${id}");

		if (array_key_exists($nm[0], $this->lst))
			return new ImageListCsv($nm[0]);

		throw new Exception("No exists ${nm[0]}");

	}

	public function getImgById($id) {
		$nm = explode("/", $id);
		if (count($nm) != 2)
			throw new Exception("Bad ID ${id}");

		$fld = new ImageListCsv($nm[0]);
		return $fld->getImage((int)$nm[1]);
	}

	public function query($query) {
		$qe = new QueryEngine($query);

		$resultset = array();

		foreach($this->getIterator() as $dir) {
			$resultset = array_merge($resultset, $this->queryDirectory($dir, $qe));
		}

		return $resultset;
	}

	private function queryDirectory($im, $query) {
		$ret = array();
		for ($i = 0; $i < $im->count(); $i++) {
			$img = $im->getImage($i);

			if ($query->match($img->getTags())) {
				$ret[] = $img;
			}
		}

		return $ret;
	}
}
