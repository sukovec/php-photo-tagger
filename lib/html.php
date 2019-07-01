<?php


function form_start($action, $method = "POST", $id=false) {
	if ($id === false)
		return "<form action='${action}' method='${method}'>";
	else 
		return "<form action='${action}' method='${method}' id='${id}'>";
}

function form_end() {
	return "</form>";
}

function output_foot() {
?>
</body>
</html>
<?php
}

function output_head($title = "Cau Saryku") {
	header("Content-type: text/html; charset=utf-8");
?>
<html>
<head><title><?php echo $title?></title>
<style>

.padded {
	padding: 5px;
}

.greyback {
	background-color: #C0C0C0;
}

.perfect {
        color: #000000;
}

.untouched {
        color: #FF0000;
        font-weight: bold;
}

.notbad {
        color: #b07000;
}

.navig { 
margin-top: 50px;
}
.navig a {
	font-size: 45px;
	padding: 25px;
	color: black;
	border: 2px solid #000000;
	border-radius: 1em;
	background: #C0FFC0;
}

/* image-checkboxes */
.imgcheck {
	display: inline-block;
}

.imgcheck input {
	display: none;
}

.imgcheck label { 
	display: block;
	border: 6px solid #FF0000;
}

.imgcheck input:checked + label {
	border: 6px solid #00FF00;
}

.imgcheck.othertag label {
	border: 6px solid #0000FF;
}

/* hideable "window" */

.switchable label {
	display: none;
}

.switchable input:checked + label {
	display: block;
}


/* tag-checkboxes */

.cbox {
	display: inline-block;
}
.cbox input { 
	display: none;
}
.cbox label {
	display: block;
	width: 140px;
	height: 45px;
	padding-top: 20px;

	background: #C0C0C0;
	border: 1px solid #000000;
	border-radius: 5em;
	text-align: center;
}

.cbox input:checked + label {
	background-color: #80FFC0;
}

.descript * {
	font-size: 34px;
}
.descript input[type="text"] {
	width: 80%;
}

.descript input[type="submit"] {
	width: 20%;
}

</style>
<body>
<a href="index.php">Index</a> | <a href="query.php">Query</a> | <a href="taglist.php">TagList</a> | <a href="persistlist.php">Persistent image link list</a> | <a href="multitag2.php">Multitag by tag</a> | <a href="setid.php">SetID</a> | <a href="viewcurrent.php">Watch it</a> <br />
<?php
}
