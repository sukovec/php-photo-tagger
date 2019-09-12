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
<head>
	<title><?php echo $title?></title>
	<link rel="stylesheet" type="text/css" href="static/css.css" />
	<script src="static/script.js" type="text/javascript"></script>
</head>

<body>
<a href="index.php">Index</a> | <a href="query.php">Query</a> | <a href="taglist.php">TagList</a> | <a href="persistlist.php">Persistent image link list</a> | <a href="multitag2.php">Multitag by tag</a> | <a href="setid.php">SetID</a> | <a href="viewcurrent.php">Watch it</a> <br />
<?php
}
