<?php
   /***************   some Universal Constants here        **********************/
   /*************/     $script_name = "";         /********************/
  /*************/      $site_name = "";         /*******************/
 /*************/       $site_link = $_SERVER['SERVER_NAME'];  /******************/
/**************        END OF UNIVERSAL CONSTANTS           ******************/
?>
<html>
<head>
      <title><?php echo $script_name ?> Online Script</title>
      <link rel="stylesheet" type="text/css" href="style.css">
      <link rel="stylesheet" type="text/css" href="style2.css">

</head>
<body>
                <center><font color="red" size="5"><strong><b> <?php echo $script_name ?></b></strong></font><br><br>



<?php


        $wordToFind = $_GET["word"];
        $url = "https://dictionary.cambridge.org/search/english/direct/?q=".$wordToFind;
        $full_url = $_SERVER['REQUEST_URI']; $qurey_url = '?'.$_SERVER['QUERY_STRING']; 
        $full_url1 = str_replace($qurey_url,'', $full_url); 
//$url3hindi = "http://google-translator.7e14.starter-us-west-2.openshiftapps.com/index.php?word=".$wordToFind;

             // header("Content-Type:text/plain");
             // 1. initialize
                        $ch = curl_init();
 
             // 2. set the options, including the url
                        $agent = 'Mozilla/5.0 (Windows NT 6.2; WOW64; rv:17.0) Gecko/20100101 Firefox/17.0';
                        curl_setopt($ch, CURLOPT_USERAGENT, $agent);
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_HEADER, true); //to get redirect location
                        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                       // curl_setopt($ch, CURLOPT_HEADER, 0);
 
             // 3. execute and fetch the resulting HTML output
                        $output = curl_exec($ch);
                   //3.1 To capture the name of the word whom i am searching by capturing the redirect url
                         if (preg_match('~Location: (.*)~i', $output, $matcho)) {
                                   $location = trim($matcho[1]);
                                                                                 }
                                   $location = str_replace('https://dictionary.cambridge.org/dictionary/english/','',$location);
                                   $location = str_replace('?q=',"\n \n / \n \n",$location);
             // 4. free up the curl handle
                        curl_close($ch);
             // 5. find matching result
                     $re = '/<b class="def">(.*?)<\/b>/';
            	     $count = preg_match_all($re, $output, $matches); //back code is used to display result in for loop $count = preg_match_all($re, $output, $matches, PREG_SET_ORDER, 0);
                 //5.1 modification on 29-3-2018 to display verb or noun
                       //$re00 = '/entry-body__el clrd js-share-holder(.*?)<\/div>\n\s+<\/div>\n\s+<\/div>\n\s+<\/div>/s';
                       $re00 = '/entry-body__el clrd js-share-holder(.*?)<\/div><\/div>/s'; //regex giving error of Catastrophic backtracking 26 Aug 2018 Edit: Now working fine 31 Aug 2018
                       //$re02 = '/<div class="smartt"[^>]*>.*?<\/div>/is';
                       $re02 = '/<div class="smartt"[^>]*>.*?<\/div>\n\s+<\/div>\n\s+<\/div>/s';                       
                       $re03 = '/<a href=\"http.*?\/topics\\/pride\\/mocking-and-taunting\\/[^>]*>.*?<\/b>/is'; //there 4was a sitename
                       $re04 = '/<div class="cols__col"[^>]*>.*?<\/div><\/div>/is'; //<div class="cols__col">
                       $re05 = '/Synonyms and related words for.*<\/p>/miXuU';
                       $count = preg_match_all($re00, $output, $matches);

                 //5.2 modification on 29-3-2018 to display defination 
                     // $re_def = '/<span title="Example" class="eg">(.*?)<\/span><\/div>/';
                    //  $result_def_store = preg_match_all($re_def, $output, $result_def_matches);
                    //   print_r($result_def_matches);
                 //5.3 hindi word is added
                       //$hindii = file_get_contents($url3hindi); this was old site url 
                       include "hindi1.php"; //this will give output in $hindii and use $wordToFind1 as input
                       $result_hindi = strip_tags($hindii); // strip tag is used remove html tags from output
              // 6. copying array output in a single file 
                                                                                 /*for($i=0;$i<$count;$i++) {  print_r ($matches[$i][1]);  echo "<br>";   }*/
                     $result = implode('<br><br>*******',$matches[0]);
                     // echo $result;
                       $result = str_replace('dictionary.cambridge.org',$site_link.$full_url1,$result);
                       $result = str_replace('index.php','',$result);
                       $result = str_replace('english/','word.php?word=',$result);
                       //$result = str_replace('https','http',$result);   //use this if your site doesn't support https
                       $result = str_replace('entry-body__el clrd js-share-holder">','',$result);
                       $result = str_replace('See more results','',$result);
                       $result = str_replace('help/codes.html','index.php',$result);
                       $result = str_replace('&raquo;','',$result);
                       $result = preg_replace($re02, '', $result);
                       $result = preg_replace($re03, '</div></a>', $result);
                       $result = preg_replace($re04, '', $result);
                       $result = str_replace('/dictionary/','',$result);

                   //6.1 modded
                      /*  $re_def1 = '/:/';
                        $result_def = $result;
                       $result_strip = strip_tags($result);
                       $count_def = preg_match_all($re_def1, $result_strip, $def_store);
                       // echo $count_def;  // Display how many definitions have been found for word
                       for($i=0;$i<$count_def;$i++){ 
                                                    // $result = str_replace(': </b>','</b>'.PHP_EOL.'<br>Example-<i><font color="brown">'.$result_def_matches[0][$i]."</i></font>".PHP_EOL,$result);
                                                     $result = preg_replace('/: <\/b>/','</b>'.PHP_EOL.'<br>Example-<i><font color="brown">'.$result_def_matches[0][$i]."</i></font>".PHP_EOL, $result, 1);
                                                     $result = str_replace('dictionary.cambridge.org','google.com',$result); //site name
                                                     $result = str_replace('english/','word.php?word=',$result);
                                                     $result = str_replace('https','http',$result);
                                                    // print_r($result);
                                                    } 
                      */
                  // 7. Finding pronunciation of searched word .mp3 files  
                          //$re2 = '/https:\/\/dictionary.cambridge.org\/media\/english\/uk_pron(.*?)"/'; //for UK audio
                          $re2 = '/\/media\/english\/uk_pron(.*?)"/'; //for UK audio                         
                          //$re3 = '/https:\/\/dictionary.cambridge.org\/media\/english\/us_pron(.*?)"/'; //for Us audio              
                          $re3 = '/\/media\/english\/us_pron(.*?)"/'; //for Us audio
                          $audio_uk = preg_match($re2, $output, $audiouk);
                          $audio_us = preg_match($re3, $output, $audious);
                          $uk = $audiouk[1]; $us = $audious[1];

                     // 7.1 Finding the word pronunciation code
                             $re7 = '/<span class=".."><span class="pron">\/<span class="ipa">(.*?)\/<\/span>/';// more accurate  ".." will match either its uk or us written
                             $pron_word = preg_match_all($re7, $output, $pronword);


                  // 8. Displaying result
                                  echo "<span class='firsti'>".$location."\n</span><br><br>";
                                  echo $result;
                                  echo '<br>UK - <font color="magenta" size=6><b>'.$pronword[1][0].'</b></font><audio controls><source src="https://dictionary.cambridge.org/media/english/uk_pron'.$uk.'" /></audio>';
                                  echo '<br>US - <font color="magenta" size=6><b>'.$pronword[1][1].'</b></font><audio controls><source src="https://dictionary.cambridge.org/media/english/us_pron'.$us.'" /></audio>'; 
                                  echo "<br>".$result_hindi;
                              //echo $output;

                                  echo "<center><font color=magenta size=3><strong>------------------<a href=index.php> Go Back </a>------------------</strong></font></center>   
                                           <center><font class=heading><strong><font color=red size=5>".$site_name."</font><br>
                                           <font color=#FF1493 size=5>-- <a href=".$site_link."/contact style=text-decoration:none></a>--<br><br>
                                           </font></strong></center>";

           // Get the client ip address
           //  $ipaddress = $_SERVER['REMOTE_ADDR'];

                  //for log the data
                  // $posts = file_get_contents("posts.txt");
                  //   $posts = "$whomtosent\n" . $posts;
                  //   $posts = "$ipaddress\n" . $posts;
                  // file_put_contents("posts.txt", $posts);
    
?>
