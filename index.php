<?php

require "base.php";


output_head();

echo "<table>";
echo "<tr><th>Folder</th><th>MultiTag</th><th>Count total</th><th>Untagged</th><th>Places</th></tr>";

$tagw = new TagWork();

$dirl = new ImageFolderList();
$itr = $dirl->getIterator();

foreach ($itr as $dir => $dirobj) {
	echo "<tr>";
	echo "<td><a href='list-folder.php?id=", $dirobj->getPath(), "'>";
	echo $dir;
        echo "</a></td>";

	echo "<td><a href='multitag.php?dir=", $dirobj->getPath(), "'>MULTITAG</a></td>";

        $cnt = $dirobj->count();
        $utc = $dirobj->untaggedCount();
        $cls = "";
        if ($utc == $cnt) 
                $cls = "untouched";
        else if ($utc == 0)
                $cls = "perfect";
        else
                $cls = "notbad";

        echo "<td>$cnt</td>";
        echo "<td class='${cls}'>${utc}</td>";

	$places = implode(", ", array_map(function($itm) { return $itm->getSelectedSubtag()->getName(); }, $tagw->getDirectorySubtagSet($dirobj, "place")));
	echo "<td>${places}</td>";




        echo "</tr>";
}

echo "</table>";

output_foot();
