<?php
// the same as multitag.php, but to tag photos tagged by some base-tag
require "base.php";

output_head("Set sub-tags to all tagged");

echo "<hr />";

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

if (!inget("searchby")) {
	echo "<h2>Select sub-tags</h2>";
	$tags = $tgs->getTags();

	foreach ($tags as $tag) {
		$base = $tag->getBaseName();
		echo "<a href='multitag2.php?searchby=${base}'>${base}</a> | ";
	}
} else if (inget("searchby") && !inget("settag")) {
	echo "<h2>Search images by tag: ${_GET["searchby"]}, give them: </h2>";

	$tags = $tgs->getTags();

	foreach ($tags as $tag) {
		$base = $tag->getBaseName();
		echo "<div class='multitag'><h3>${base}</h3>";

		foreach($tag->getSubtags() as $sub) {
			$canotag = $sub->getNotation();
			$subname = $sub->getName();
			echo "<a href='multitag2.php?searchby=${_GET["searchby"]}&amp;settag=${canotag}'>${subname}</a> | ";
		}

		echo "</div>";
	}
} else if (inget("settag") && !inget("searchby")) { 
	throw new Exception("settag in GET but not searchby?"); 
} else {
	echo "<h2>Setting '${_GET["settag"]}' to '${_GET["searchby"]}'</h2>";
	?> <label>Select all<input type="checkbox" onclick="toggle(this, 'theformular')" /></label> <?php
	echo form_start("setmultitag.php", "POST", "theformular");

	$im = new ImageFolderList();
	$found = $im->query($_GET["searchby"]);
	foreach ($found as $img) {
		$tagset = new ImageTagSet($img, $tgs);

		$imgid = $img->getId();

		$havetag = false;
		$havefull = false;

		if ($tagset->hasBaseTag($_GET["settag"]))
			$havetag = true;

		if ($tagset->hasFullTag($_GET["settag"]))
			$havefull = true;

		if ($havefull) // print hidden for resetting, when unselected checkbox
			echo "<input type='hidden' name='${imgid}' value='DEL:${_GET["settag"]}' />"; //this may not work in other languages or frameworks

		echo "<div class='imgcheck" . ($havetag ? " othertag" : "") . "'>";
		echo "<input type='checkbox' name='${imgid}' value='SET:${_GET["settag"]}' id='tag_${imgid}' " . ($havefull ? "checked='checked'" : "") . " />";
		echo "<label for='tag_${imgid}'><img src='getimg.php?type=thumb&amp;img=${imgid}' width='175' /></label>";
		echo "</div>";
	}

	echo "<div><input type='submit' value='SET IT YEEEAAAH' /></div>";
	echo form_end();
}

output_foot();
