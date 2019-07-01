<?php

require "base.php";

output_head("Los fotos");

$tagw = new TagWork();

?>
<div class="switchable">
	<input type="checkbox" name="cb_filters" <?php if(inget("filter")) echo "checked='checked'"; ?> /> Place filters
	<label for="cb_filters" class="padded greyback">
		<h5>Select the place</h5>
		<a href="index.php">NADA</a><br />

		<?php
			$tag = $tagw->getTag("place");
			$tags = $tag->getSubtags();
			foreach($tags as $tg) {
				echo "<a href='?filter=", $tg->getNotation(), "'>", $tg->getName(), "</a> | ";
			}
		?>
	</label>
</div>

<!--
<div class="switchable">
	<input type="checkbox" name="cb_author" <?php if(inget("authors")) echo "checked='checked'"; ?> /> Select authors:
	<label for="cb_author" class="padded greyback">
		<h5>Select authors</h5>
		<?php if (inget("filter")) $addquery = "&amp;filter=" . urlencode($_GET["filter"]); ?>
		<form action="index.php?authors=true<?php echo $addquery;?>">
			<label><input type="checkbox" name="author[]" value="sarka" />Sarka</label> | 
			<label><input type="checkbox" name="author[]" value="sukofon" />Sukofon</label> | 
			<label><input type="checkbox" name="author[]" value="fotak" />Fotak</label> | 
		</form>

	</label>
</div>
-->

<table>
<tr><th>Folder</th><th>MultiTag</th><th>Count total</th><th>Untagged</th><th>Places</th></tr>
<?php 

$dirl = new ImageFolderList();
$itr = $dirl->getIterator();

$filter = false; 
if (inget("filter")) {
	$filter_tag = Tag::parseBaseName($_GET["filter"]);
	if ($filter_tag !== "place") throw new Exception("Neumim");

	$filter = $_GET["filter"];
}

foreach ($itr as $dir => $dirobj) {
	$subset = $tagw->getDirectorySubtagSet($dirobj, "place");
	if ($filter && !array_key_exists($filter, $subset)) continue;
	$places = implode(", ", array_map(function($itm) { return $itm->getSelectedSubtag()->getName(); }, $subset));

	


	echo "<tr>";
	echo "<td><a href='list-folder.php?id=", $dirobj->getPath(), "'>";
	echo $dir;
        echo "</a></td>";

	echo "<td><a href='multitag.php?dir=", $dirobj->getPath(), "'>MT</a></td>";

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

	echo "<td>${places}</td>";




        echo "</tr>";
}

echo "</table>";

output_foot();
