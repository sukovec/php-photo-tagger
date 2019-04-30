<?php

require "base.php";

output_head("Seznam tagiku");
$iml = new ImageFolderList();
$tgs = new TagWork();

echo "<ul>";
foreach($tgs->getTags() as $tag) {
	$name = $tag->getBaseName();

	$f = $iml->query($name);


	echo "<li><a href='query.php?query=${name}'>$name</a>: ", count($f), " kousku</li>";
}
echo "</ul>";


output_foot();

