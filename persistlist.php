<?php

require "base.php";

$options = ['dir' => PHPATH, "ext" => ".persist" ];
$persists = new Flintstone\Flintstone('fotolinks', $options);

if (array_key_exists("adt", $_POST) && array_key_exists("id", $_POST)) {
	$val = $persists->get($_POST["id"]);
	$val["adt"] = $_POST["adt"];
	$persists->set($_POST["id"], $val);
}

$keys = $persists->getKeys();

output_head("Persisted link list");
echo "<table><tr><th>Persist ID + link</th><th>img id</th><th>Viewcount</th><th>Change adt text</th></tr>";

for ($i = 0; $i < count($keys); $i++) {
	$cur = $persists->get($keys[$i]);
	//$cur["seen"] = [ "raw" => 0, "full" => 0, "thumb" => 0 ];
	//$persists->set($keys[$i], $cur);
	echo "<tr>",
		"<td>", "<a href='/persist/${keys[$i]}'>{$keys[$i]}</a>", "</td>",
		"<td>", "<a href='tag.php?img={$cur["img"]}'>${cur["img"]}</a>", "</td>",
		"<td>thumb: <b>", $cur["seen"]["thumb"], "</b> | raw: <b>", $cur["seen"]["raw"], "</b> |  full: <b>", $cur["seen"]["full"], "</b> </td>",
		"<td>",
		
		"<form method='POST'><input type='text' name='adt' value='${cur["adt"]}' /><input type='hidden' name='id' value='${keys[$i]}' /><input type='submit' /></form>",

		"</td>",
		"</tr>";


}
