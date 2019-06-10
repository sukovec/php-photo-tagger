<?php

require "base.php";


output_head();

echo "<ul>";

$tagw = new TagWork();

$dirl = new ImageFolderList();
$itr = $dirl->getIterator();

$lastplaces = "";

foreach ($itr as $dir => $dirobj) {
	// first display location
	$places = implode(", ", array_map(function($itm) { return $itm->getSelectedSubtag()->getName(); }, $tagw->getDirectorySubtagSet($dirobj, "place")));
	if ($places != $lastplaces) {
		echo "<li><h2>$places</h2></li>";
		$lastplaces = $places;
	}



	echo "<li>";
	echo "<a href='list-folder.php?id=", $dirobj->getPath(), "'>";
	echo $dir;
        echo "</a> | ";

	echo "<a href='multitag.php?dir=", $dirobj->getPath(), "'>MULTITAG</a>";

        $cnt = $dirobj->count();
        $utc = $dirobj->untaggedCount();
        $cls = "";
        if ($utc == $cnt) 
                $cls = "untouched";
        else if ($utc == 0)
                $cls = "perfect";
        else
                $cls = "notbad";

        echo " ::: Total: ", $cnt;
        echo " ::: <span class='${cls}'>Untagged: ", $utc, "</span>";


        echo "</li>";
}

echo "</ul>";

output_foot();
