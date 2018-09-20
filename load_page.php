<?php
if(!$_POST['page']) die("0");

$rawdata11 = file_get_contents("postgood.txt");
$image_url = $_POST['page'];
$image_url = str_replace('#','',$image_url);
$re00 = '/<a href="#'.$image_url.'">&#10062;<\/a>/';
$re01 =  '<a href="#'.$image_url.'">&#9989;</a>';
$re001 = '<a href="#'.$image_url.'">&#10062;</a>';
$alreay_count = preg_match_all($re00, $rawdata11, $matches);
if ($alreay_count >= "1") { $rawdata11 = str_replace($re001,$re01,$rawdata11); $msg = $re01;  file_put_contents("postgood.txt",$rawdata11); }
else                      { $rawdata11 = str_replace($re01,$re001,$rawdata11); $msg = $re001; file_put_contents("postgood.txt",$rawdata11); }

//$envc = file_get_contents($image_url);
//$imageDataEncoded = base64_encode($envc);
//echo '<img src="data:image/gif;base64,'.$imageDataEncoded.'" />';

echo $msg;
?>
