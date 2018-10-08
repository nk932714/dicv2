<?php

require_once ('autoload.php');
use \Statickidz\GoogleTranslate;
include 'GoogleTranslate.php';
$source = 'null';
$target = 'hi';
$text = $wordToFind;
$trans = new GoogleTranslate();
$hindii = $trans->translate($source, $target, $text);


?>
