<?php
if (php_sapi_name() !== 'cli') die("Not run from cli");


include "base.php";

class Frmtr implements Flintstone\Formatter\FormatterInterface
{
	public function encode($data): string {
		if (!is_string($data))
			throw new Exception("What?");

		return $data;
	}
	public function decode(string $data) {
		return $data;
	}
}

$options = ['dir' => PHPATH, "ext" => ".translate", "formatter" => new Frmtr() ];
$persists = new Flintstone\Flintstone('tagtranslate', $options);

$tw = new TagWork();

$tags = $tw->getTags();

function findOrCreate($tag, $persists) {
	$canotag = $tag->getNotation();

	$canotag = str_replace(":", "___", $canotag);

	$tagdata = $persists->get($canotag);
	if ($tagdata === false) 
		$persists->set($canotag, "");
}

foreach ($tags as $tag) {
	findOrCreate($tag, $persists);
	foreach($tag->getSubtags() as $sub) {
		findOrCreate($sub, $persists);
	}
}
