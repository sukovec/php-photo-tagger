<?php
require "base.php";

if (!inget("dir"))
	throw new Exception("No ID in get");

output_head();


?>
<script>

function toggle(source, formid) {
	var checkboxes = document.getElementById(formid).querySelectorAll('input[type="checkbox"]');
	for (var i = 0; i < checkboxes.length; i++) {
		if (checkboxes[i] != source)
			checkboxes[i].checked = source.checked;
	}
}
</script>


<?php

$tgs = new TagWork();

if (!inget("tag")) {
	$tags = $tgs->getTags();

	foreach ($tags as $tag) {
		$base = $tag->getBaseName();
		echo "<div class='multitag'><h3>${base}</h3>";

		foreach($tag->getSubtags() as $sub) {
			$canotag = $sub->getNotation();
			$subname = $sub->getName();
			echo "<a href='multitag.php?dir=${_GET["dir"]}&amp;tag=${canotag}'>${subname}</a> | ";
		}

		echo "</div>";
	}
} else {
	$dirl = new ImageFolderList();
	$imgs = $dirl->getImgList($_GET["dir"]);

	?> <label>Select all<input type="checkbox" onclick="toggle(this, 'theformular')" /></label> <?php
	echo form_start("setmultitag.php?dir=${_GET["dir"]}", "POST", "theformular");

	for ($i = 0; $i < $imgs->count(); $i++) {
		$img = $imgs->getImage($i);
		$tagset = new ImageTagSet($img, $tgs);

		$imgid = $img->getId();

		$havetag = false;
		$havefull = false;

		if ($tagset->hasBaseTag($_GET["tag"]))
			$havetag = true;

		if ($tagset->hasFullTag($_GET["tag"]))
			$havefull = true;

		if ($havefull) // print hidden for resetting, when unselected checkbox
			echo "<input type='hidden' name='${imgid}' value='DEL:${_GET["tag"]}' />"; //this may not work in other languages or frameworks

		echo "<div class='imgcheck" . ($havetag ? " othertag" : "") . "'>";
		echo "<input type='checkbox' name='${imgid}' value='SET:${_GET["tag"]}' id='tag_${imgid}' " . ($havefull ? "checked='checked'" : "") . " />";
		echo "<label for='tag_${imgid}'><img src='getimg.php?type=thumb&amp;img=${imgid}' width='175' /></label>";
		echo "</div>";
	}

	echo "<div><input type='submit' value='SET IT YEEEAAAH' /></div>";
	echo form_end();
}

output_foot();
