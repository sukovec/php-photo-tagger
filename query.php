<?php

require "base.php";

$im = new ImageFolderList();
$query = "";
if (inget("query"))
        $query = $_GET["query"];

output_head("Hledanicko, joo: " . $query);

echo "<form action='query.php' method='GET'>";
echo "<input type='text' name='query' value='${query}' /><input type='submit' value='Hledat' />";
echo "</form>";

echo "<br /><hr /><br />";

if ($query == "") {
        output_foot();
        die();
}

$found = $im->query($query);
foreach ($found as $img) {
	$id = $img->getID();

	echo "<a href='tag.php?img=${id}'>";
	echo "<img width='128' src='getimg.php?type=thumb&amp;img=${id}' />";
	echo "</a>";
}

output_foot();
