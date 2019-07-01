<?php

require "base.php";

if (!inget("myid")) die("<form action='setid.php' method='GET'><input type='text' name='myid' /></form>");

setcookie("mywatchid", $_GET["myid"]);

header("HTTP/1.1 307 See Other");
header("Location: index.php");
