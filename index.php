<?php
    /***************   some Universal Constants here        **********************/
   /*************/     $script_name = " ";         /********************/
  /*************/      $site_name = "";         /*******************/
 /*************/       $site_link = $_SERVER['SERVER_NAME'];  /******************/
/**************        END OF UNIVERSAL CONSTANTS           ******************/
$whomtosent     = $_POST["whomtosent"];
//$whomtosents    = $whomtosent;
if (!isset($_POST['submit'])) { // if page is not submitted to itself echo the form
?>


<html>
<head>
      <title><?php echo $script_name ?> </title>
      <link rel="stylesheet" type="text/css" href="style.css">
      <link href="style2.css" rel="stylesheet" type="text/css" />
      <link href="style.css" rel="stylesheet" type="text/css" />

</head>
<body>
                <center><font color="red" size="5"><strong><b> <?php echo $script_name ?> </b></strong></font><br>
                <form method="post" action="<?php  echo $PHP_SELF; ?>">
                         <input type="text" class="text" maxlength="99" name="whomtosent" placeholder="Word"><br><br>
                         <input type="submit" name="submit" class="button" value="Submit">			
                </form></center>
                       <center><font class="heading"><strong><font color="red" size="5"><a href="<?php echo $site_link?>" style="text-decoration:none">  <?php echo $site_name ?>  </a></font><br>
                       <font color="#FF1493" size="2"> <a href="history.php" style="text-decoration:none">History</a><br><br>
                       </font></strong></center>
<?php
     } //!isset($_POST['submit'])  closing of this
else {
         // Your work goes here
	 
		 
        $wordToFind = mb_strtolower($whomtosent);  // convert capital latter to lower case
        //$wordToFind = rtrim($wordToFind);         // this will trim the space at right side or ending space
        //$wordToFind1 = urlencode("$wordToFind"); // any space in searched word will cause error for file_get_contents alternatively we can use cURL
                $lastchar = substr($wordToFind, -1);
                $last_space = preg_match_all('/\\s/', $lastchar, $matches);
                $z=1;
                if($last_space >= $z) { $wordToFind01 = rtrim($wordToFind);  }       // this will trim the space at right side or ending space 
                else { $wordToFind01 = $wordToFind; }
        $url = "https://dictionary.cambridge.org/dictionary/english/".$wordToFind;
        $url2 = "https://dictionary.cambridge.org/spellcheck/english/?q=".$wordToFind;
        $wordToFind1 = urlencode("$wordToFind01"); // any space in searched word will cause error for file_get_contents alternatively we can use cURL
        //below to get the url where this php is installed or pasted
        $full_url = $_SERVER['REQUEST_URI']; $qurey_url = '?'.$_SERVER['QUERY_STRING']; 
        $full_url1 = str_replace($qurey_url,'', $full_url); 
        
        //$url3hindi = "http://google-translator.7e14.starter-us-west-2.openshiftapps.com/index.php?word=".$wordToFind1; //this was old other site url
             // header("Content-Type:text/plain");
             // 1. initialize
                        $ch = curl_init();
 
             // 2. set the options, including the url
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HEADER, 0);
 
             // 3. execute and fetch the resulting HTML output
                        $output = curl_exec($ch);
 
             // 4. free up the curl handle
                        curl_close($ch);
             // 5. find matching result
                     $re = '/<b class="def">(.*?)<\/b>/';
            	     $count = preg_match_all($re, $output, $matches); //back code is used to display result in for loop $count = preg_match_all($re, $output, $matches, PREG_SET_ORDER, 0);
                     //echo $count;
             // if matches found then it will collect & display the result. and if matches not found it will display matching words
                     $x=1; // assume minimum matches is equal to one
 if($count >= $x) {
     
                    // heading of echo word
                       $headword = $wordToFind;
                 //5.1 modification on 29-3-2018 to display verb or noun
                       //$re00 = '/entry-body__el clrd js-share-holder(.*?)<\/div>\n\s+<\/div>\n\s+<\/div>\n\s+<\/div>/s';
                       $re00 = '/entry-body__el clrd js-share-holder(.*?)<\/div><\/div>/s'; //regex giving error of Catastrophic backtracking 26 Aug 2018 Edit: Now working fine 31 Aug 2018
                       //$re02 = '/<div class="smartt"[^>]*>.*?<\/div>/is';
                       $re02 = '/<div class="smartt"[^>]*>.*?<\/div>\n\s+<\/div>\n\s+<\/div>/s';
                       //$re03 = '/<a href=\"https:\\/\\/google\.com\\/topics\\/pride\\/mocking-and-taunting\\/[^>]*>.*?<\/b>/is'; //there was a sitename
                       $re03 = '/<a href=\"http.*?\/topics\\/pride\\/mocking-and-taunting\\/[^>]*>.*?<\/b>/is'; //there 4was a sitename
                       $re04 = '/<div class="cols__col"[^>]*>.*?<\/div><\/div>/is'; //<div class="cols__col">
                       $re05 = '/Synonyms and related words for.*<\/p>/miXuU';
                       $count = preg_match_all($re00, $output, $matches);
                 //5.2 modification on 29-3-2018 to display defination (now in new file def is already included)
                      // $re_def = '/<span title="Example" class="eg">(.*?)<\/span><\/div>/';
                      //$result_def_store = preg_match_all($re_def, $output, $result_def_matches);
                 //5.3 hindi word is added
                       //$hindii = file_get_contents($url3hindi); this was old site url 
                       include "hindi.php"; //this will give output in $hindii and use $wordToFind1 as input
                       $result_hindi = strip_tags($hindii); // strip tag is used remove html tags from output
                       
                 // 6. copying array output in a single file     /*for($i=0;$i<$count;$i++) {  print_r ($matches[$i][1]);  echo "<br>";   }*/
                       $result = implode('<br><br>',$matches[0]);
                       //$result = str_replace('dictionary.cambridge.org','google.com',$result); //site name
                       $result = str_replace('dictionary.cambridge.org',$site_link.$full_url1,$result); //site name
                       $result = str_replace('index.php','',$result);
                       $result = str_replace('english/','word.php?word=',$result);
                       $result = str_replace('https','http',$result);
                       $result = str_replace('entry-body__el clrd js-share-holder">','',$result);
                       $result = str_replace('See more results','',$result);
                       $result = str_replace('help/codes.html','index.php',$result);
                       $result = str_replace('&raquo;','',$result);
                       $result = preg_replace($re02, '', $result);
                       $result = preg_replace($re03, '</div></a>', $result);
                       $result = preg_replace($re04, '', $result);
                       $result = preg_replace($re05, '"></div></a>', $result);
                       $result = str_replace('/dictionary/','',$result);
                   //6.1 modded  (This paragraph will find the words which have examples and attach the examples with them
                       // $re_def1 = '/:/';
                       //$result_def = $result;
                       // $result_strip = strip_tags($result);
                       // $count_def = preg_match_all($re_def1, $result_strip, $def_store);
                       // echo $count_def;  // Display how many definitions have been found for word
                       //for($i=0;$i<$count_def;$i++){ 
                                                      // $result = str_replace(': </b>','</b>'.PHP_EOL.'<br>Example-<i><font color="brown">'.$result_def_matches[0][$i]."</i></font>".PHP_EOL,$result);   
                                                      // $result = preg_replace('/: <\/b>/','</b>'.PHP_EOL.'<br>Example-<i><font color="brown">'.$result_def_matches[0][$i]."</i></font>".PHP_EOL, $result, 1);  // str_replace is not used beacuse it replaces all matching words
                                                      // $result = str_replace('dictionary.cambridge.org','google.com',$result); //site name without http
                                                      // $result = str_replace('english/','word.php?word=',$result);
                                                      // $result = str_replace('https','http',$result);
                                                   // } 
                // 7. Finding pronunciation of searched word .mp3 files  
                         //$re2 = '/https:\/\/dictionary.cambridge.org\/media\/english\/uk_pron(.*?)"/'; //for UK audio old
                         $re2 = '/\/media\/english\/uk_pron(.*?)"/'; //for UK audio                         
                         //$re3 = '/https:\/\/dictionary.cambridge.org\/media\/english\/us_pron(.*?)"/'; //for Us audio old
                         $re3 = '/\/media\/english\/us_pron(.*?)"/'; //for Us audio
                         $audio_uk = preg_match($re2, $output, $audiouk);
                         $audio_us = preg_match($re3, $output, $audious);
                         $uk = $audiouk[1]; $us = $audious[1];
                     // 7.1 Finding the word pronunciation code
                             $re7 = '/<span class="uk"><span class="pron">\/<span class="ipa">(.*?)\/<\/span>/';
                             $pron_word = preg_match_all($re7, $output, $pronword);
                // 8. Displaying result
                      //  echo $result;
                      // ?><!-- <br>UK<audio controls><source src="https://dictionary.cambridge.org/media/english/uk_pron<?php echo $uk; ?>" /></audio> --><?php
                      // ?><!-- <br>US<audio controls><source src="https://dictionary.cambridge.org/media/english/us_pron<?php echo $us; ?>" /></audio> --><?php               
  
             } // closing of  if($count >= $x)
    else
        {
                         $headword = "No Result found for <b><i><font color=green size=4>".$wordToFind."</b></i></font>. Please try matching words below";
                         $bottomword = "<u>Note</u>- If you haven't found the correct matching word then try to delete the last word of your search and search again"; 
                       // header("Content-Type:text/plain");
                  // 1. initialize new Curl for suggestion word if searched word is wrong
                        $ch2 = curl_init();
 
                  // 2. set the options, including the url
                        curl_setopt($ch2, CURLOPT_URL, $url2);
                        curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch2, CURLOPT_HEADER, 0);
 
                  // 3. execute and fetch the resulting HTML output
                        $output2 = curl_exec($ch2);
 
                  // 4. free up the curl handle
                        curl_close($ch2);
                  // 5. find matching result
                       //$ree = '/<span class="prefix-item">(^|\s)(.*?)<\/b><\/span>/s';  //(^|\s) is used to ignore line break or space in regex
                       $ree = '/<ol class="unstyled prefix-block a--b a--rev">(.*?)<\/ol>/s';
            	       $counte = preg_match_all($ree, $output2, $matchese); //back code is used to display result in for loop $count = preg_match_all($re, $output, $matches, PREG_SET_ORDER, 0);
                 // 6. copying array output in a single file 
                       $result = implode('<br><br>',$matchese[0]);
                       $result = str_replace('https','http',$result); $search  = array(0,1,2,3,4,5,6,7,8,9);
                       $result = str_replace($search,'',$result);
                       $result = str_replace('dictionary.cambridge.org',$site_link.$full_url1,$result);
                       $result = str_replace('index.php','',$result);
                       $result = str_replace('english/','Word.php?word=',$result);                // its Capital Word.php not word.php
                       $result = str_replace('direct/?q=','',$result);
                       $result = str_replace('search','dictionary',$result);
                        $result = str_replace('/dictionary/','',$result);
                   
         }// closing of else of this expression if($count >= $x)
      
               // Final result display
                       echo "<center><font class=heading><strong><font color=red size=5>".$script_name." </font></strong><br><br><br>";          //heading              
                       echo "<span class='firsti'>".$headword."\n</span><br><br>";                                                                       //Echo MAIN data
                       echo '<html> <head></head> <link href="style2.css" rel="stylesheet" type="text/css" /> <link href="style.css" rel="stylesheet" type="text/css" />';
                       echo $result;
                       if($count >= $x) {  echo '<br>UK - <font color="magenta" size=6><b>'.$pronword[1][0].'</b></font><audio controls><source src="https://dictionary.cambridge.org/media/english/uk_pron'.$uk.'" /></audio>';
                                           echo '<br>US - <font color="magenta" size=6><b>'.$pronword[1][1].'</b></font><audio controls><source src="https://dictionary.cambridge.org/media/english/us_pron'.$us.'" /></audio>'; 
                                           echo "<br>".$result_hindi;
                                        }
                       echo "<center>".$bottomword."</center>";
                       echo "<center><font color=magenta size=3><strong>------------------<a href=index.php> Go Back </a>------------------</strong></font></center>   
                       <center><font class=heading><strong><font color=red size=5>   ".$site_name."  </font><br>
                       <font color=#FF1493 size=5> <a href=".$site_link."/contact style=text-decoration:none></a><br><br></font></strong></center>";
                             
                     if($count >= $x) {
                                             $history = file_get_contents("postgood.txt");
                                             $logdata = $history."<li><a href=word.php?word=".$wordToFind01.">".$wordToFind01."</a>=".$result_hindi.'<div class="inline" id="#'.$wordToFind01.'"><a href="#'.$wordToFind01.'">&#10062;</a></div></li>';
                                             $history_count = strpos($history, $wordToFind01); 
                                                if($history_count >= $x) { /* echo "hola";*/ }
                                                else { 
                                                  file_put_contents("postgood.txt",$logdata); 
                                                  
                                                 }
                                       }
                                         
    }
?>

<head><title> <?php echo $script_name ?> </title><link rel="stylesheet" type="text/css" href="style.css">
      <link href="style2.css" rel="stylesheet" type="text/css" />
</head>	
</body>
</html>
