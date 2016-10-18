<?php

include("../src/mime.php");


var_dump(MimeTypeByFilename(__FILE__));

var_dump(MimeTypeByExtension(".jpg"));