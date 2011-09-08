<?php include("inc/header-php.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<![if !IE]><html xmlns="http://www.w3.org/1999/xhtml"><![endif]>
<!--[if lt IE 9]><html xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml"><![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quizroo</title>
<?php include("inc/header-css.php");?>
<link href="css/dashboard.css" rel="stylesheet" type="text/css" />
<link href="css/recent.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="fb-root"></div>
<div>
<?php include("../modules/statusbar.php"); ?>
<?php require('../modules/quizrooDB.php'); ?>
<?php require('../modules/quiz.php'); ?>
<?php require('../modules/variables.php');

//if function needs to be done is recommedation 
//update all the recommendation into database: tranverse through checkbox group
if(isset($_GET['do']) && $_GET['do']=="rec"){
$array = $_POST['recommend'];
if(empty($array)){
    echo("You didn't select any quiz for recommendation.");
}

else{
    $recNum = count($array);

    echo("$recNum quizzes have been added into recommendation list.");

    for($i=0; $i < $recNum; $i++)
    {
	  // update database: call the method from quiz.php
	  $quiz_id = $array[$i] . " ";
	  $quiz = new Quiz($quiz_id);
	  $quiz->quizRecommendation($quiz_id, 1);
    }
  }
} // end of if

//if function needs to be done is recommedation 
//update all the recommendation into database: tranverse through checkbox group
if(isset($_GET['do']) && $_GET['do']=="delete"){
$array_delete = $_POST['checkbox_delete'];
if(empty($array_delete)){
    echo("You didn't select any quiz for deleting.");
}

else{
    $delNum = count($array_delete);

    echo("$delNum quizzes have been deleted from recommendation list.");

    for($i=0; $i < $delNum; $i++)
    {
	  // update database: call the method from quiz.php
	  $quiz_id = $array_delete[$i] . " ";
	  $quiz = new Quiz($quiz_id);
	  $quiz->quizRecommendation($quiz_id, 0);
    }
  }
} // end of if

// retrieve recommended quizzes
$query_recommendations = sprintf("SELECT quiz_id, quiz_name, quiz_description, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isRecommended = 1 LIMIT 0, %d", 15);
$recommendations = mysql_query($query_recommendations, $quizroo) or die(mysql_error());
$row_recommendations = mysql_fetch_assoc($recommendations);
$totalRows_recommendations = mysql_num_rows($recommendations);
?>
</div>
<!-- Display the current recommendations -->
<div id="recommendations">
      <h2>Recommended quizzes</h2>
      <table cellspacing="2" cellpadding="1" style="font-size:12px">
      <tr bgcolor="#FFCC00">
      <td>Quiz title</td>
      <td>Number of likes</td>
      <td>Quiz category</td>
      <td>Creation date</td>
      <td></td>
      </tr>
      <div class="repeat-container">
      <?php 
	  if($totalRows_recommendations != 0){ do { ?>
      <tr>
      <!-- Quiz title-->
        <td>
          <a href="previewQuiz.php?id=<?php echo $row_recommendations['quiz_id']; ?>"><?php echo $row_recommendations['quiz_name']; ?></a>
        </td>
      <!-- Number of likes-->
        <td>
			<?php if(!$GAME_ALLOW_DISLIKES){ if($row_recommendations['likes'] > 0){ ?>
            <p class="rating"><span class="like"><?php echo $row_recommendations['likes']; ?></span> <?php echo ($row_recommendations['likes'] > 1) ? "people" : "person"; ?></p>
			<?php }}else{ ?><p class="rating"><span class="like"><?php echo $row_recommendations['likes']; ?></span> likes, <span class="dislike"><?php echo $row_recommendations['dislikes']; ?></span> dislikes</p><?php } ?>
        </td>
      <!-- Quiz category-->
      <td>
      <?php echo $row_recommendations['cat_name']; ?>
      </td>
      <!-- Creation date-->
      <td>
      <?php echo $row_recommendations['creation_date']; ?>
      </td>
      <!-- Delete check box -->
      <form action="recommendationList.php?do=delete" method="post" enctype="multipart/form-data" >
	  <td><input name="checkbox_delete[]" type="checkbox" value="<?php echo $row_recommendations['quiz_id']; ?>"/></td>
	  </tr>
        <?php } while ($row_recommendations = mysql_fetch_assoc($recommendations)); }else{ ?>
        <p>There are no recommended quizzes!</p>
        <?php } ?>
        </div>
        </table>
    </div>
<?php
mysql_free_result($recommendations);
?>
  </div> 
<div align="center">
<input type="submit" value="Delete recommendation" />
</form>
</div>
</body>
</html>