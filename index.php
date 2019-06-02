<?php

require "base.php";


output_head();

?>

<div>
	<div><label>
</div>

<?

echo "<ul>";

$dirl = new ImageFolderList();
$itr = $dirl->getIterator();
foreach ($itr as $dir => $dirobj) {
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
