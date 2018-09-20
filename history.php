<html>
<head>
      <title>Cambridge Dictionary Online Script</title>
      <link href="style2.css" rel="stylesheet" type="text/css" />
      <link href="style.css" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js"></script>
      <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
  <!-- for autocomplete below lines -->
      <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
      <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
      <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
      <?php             /* this php code will get all suggestion words from the txt file */
              $history = file_get_contents("postgood.txt");
              $re_suggestions = '/href=word.php\?word=.*?>(.*?)<\/a>=/m';
              preg_match_all($re_suggestions , $history, $matches_suggestion);
              $suggestions_list = '"'.implode('","',$matches_suggestion[1]).'"';
      ?>
      <script>
        $( function() {
          var availableTags = [
            <?php echo $suggestions_list; ?>
                              ];
            $( "#tags" ).autocomplete({
            source: availableTags
                                });
               } );
      </script>
  <!-- for autocomplete above -->
      <script type="text/javascript" src="script.js"></script>
      <style> .inline {     display: inline-block;     } </style>
      <style> .pup:link, .pup:visited { background-color: #f4511e; color: white; padding: 4px 6px; text-align: center; text-decoration: none; display: inline-block; } .pup:hover, .pup:active { background-color: red; } </style>
      <style>       .button2 { display: inline-block; border-radius: 6px; background-color: #f4511e; border: none; color: #FFFFFF; text-align: center; font-size: 15px; padding: 5px; width: 120px; transition: all 0.5s; cursor: pointer; margin: 5px; } .button2 span { cursor: pointer; display: inline-block; position: relative; transition: 0.5s; } .button2 span:after { content: '\00bb'; position: absolute; opacity: 0; top: 0; right: -20px; transition: 0.5s; } .button2:hover span { padding-right: 25px; } .button2:hover span:after { opacity: 1; right: 0; } input[type=text1] { width: 130px; box-sizing: border-box; border: 5px solid #ccc; border-radius: 19px; font-size: 15px; padding: 12px 10px 12px 10px; -webkit-transition: width 0.4s ease-in-out; transition: width 0.4s ease-in-out; } input[type=text1]:focus { width: 30%; }      </style>
      <style> .ol { display: block; list-style-type: decimal; margin-top: 0em; margin-bottom: 1em; margin-left: 0; margin-right: 0; padding-left: 40px; } </style>
</head>
<body>
                            <form method="post" action="<?php  echo $PHP_SELF; ?>">
                            <input type="text1" id="tags" class="text1" maxlength="9999999999" name="word" placeholder="Word to delete from list"><br>
                            <input type="submit" name="submit" class="button2" value="Delete Word"><br>
                            <a class=pup href="./">Go Back</a>
                            </form><button onclick="sortListDir()">Sort</button>&nbsp;<a class=pup href="history.php">Click Refreash</a>&nbsp;<a class=pup href="#bottom" id="hulk">Click to go to Bottom</a>

<?php
    if(isset($_POST[submit])) {
                  header("location:history.php"); // your current page 
                  echo '<ol start="1">'; $history = file_get_contents("postgood.txt"); echo $history;  
                  $wordToDelete = $_POST["word"];
                  $wordToDelete = mb_strtolower($wordToDelete);  // convert capital latter to lower case beacuse it will match with case letter
                  $re = '/<li><a href=word.php\?word='.$wordToDelete.'>.*?<\/a>=.*?<\/li>/';
                  $history = preg_replace($re, "", $history);
                  file_put_contents("postgood.txt",$history);

                            }
     else {
                echo'<ol id="id01" start="1">';  $history = file_get_contents("postgood.txt"); echo $history; echo  '<div id="pageContent"></div>';
         }
?>
<a class=pup href="#top" id="hulk">Click to go to Top</a>
 <script type="text/javascript" src="jquery.js"></script>
 <script> function sortListDir() { var list, i, switching, b, shouldSwitch, dir, switchcount = 0; list = document.getElementById("id01"); switching = true; /*Set the sorting direction to ascending:*/ dir = "asc"; /*Make a loop that will continue until no switching has been done:*/ while (switching) { /*start by saying: no switching is done:*/ switching = false; b = list.getElementsByTagName("LI"); /*Loop through all list-items:*/ for (i = 0; i < (b.length - 1); i++) { /*start by saying there should be no switching:*/ shouldSwitch = false; /*check if the next item should switch place with the current item, based on the sorting direction (asc or desc):*/ if (dir == "asc") { if (b[i].innerHTML.toLowerCase() > b[i + 1].innerHTML.toLowerCase()) { /*if next item is alphabetically lower than current item, mark as a switch and break the loop:*/ shouldSwitch = true; break; } } else if (dir == "desc") { if (b[i].innerHTML.toLowerCase() < b[i + 1].innerHTML.toLowerCase()) { /*if next item is alphabetically higher than current item, mark as a switch and break the loop:*/ shouldSwitch= true; break; } } } if (shouldSwitch) { /*If a switch has been marked, make the switch and mark that a switch has been done:*/ b[i].parentNode.insertBefore(b[i + 1], b[i]); switching = true; /*Each time a switch is done, increase switchcount by 1:*/ switchcount ++; } else { /*If no switching has been done AND the direction is "asc", set the direction to "desc" and run the while loop again.*/ if (switchcount == 0 && dir == "asc") { dir = "desc"; switching = true; } } } } </script>
 <script type="text/javascript" src="jquery.js"></script>
