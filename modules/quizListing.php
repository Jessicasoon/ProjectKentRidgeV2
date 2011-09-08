<?php require('../modules/quizrooDB.php'); ?>
<?php require('../modules/variables.php');
// retrieve recommended quizzes
$query_recommendations = sprintf("SELECT quiz_id, creation_date, quiz_name, quiz_description, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 ORDER BY creation_date DESC LIMIT 0, %d", 15);
$recommendations = mysql_query($query_recommendations, $quizroo) or die(mysql_error());
$row_recommendations = mysql_fetch_assoc($recommendations);
$totalRows_recommendations = mysql_num_rows($recommendations);

// retrieve popular quizzes
$query_popular = sprintf("SELECT * FROM (SELECT quiz_id, creation_date, quiz_name, quiz_description, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes, quiz_score * (IF(likes > 0, likes, 0.5)) AS rankscore FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 ORDER BY rankscore DESC LIMIT 0, %d) t ORDER BY RAND() LIMIT 0, %d", $VAR_NUM_POPULAR_POOL, 15);
$popular = mysql_query($query_popular, $quizroo) or die(mysql_error());
$row_popular = mysql_fetch_assoc($popular);
$totalRows_popular = mysql_num_rows($popular);
?>
<div id="dashboard-container">
  <div class="clear">
    <div id="recommendations">
      <h2>Latest</h2>
      <table cellspacing="2" cellpadding="1" >
      <tr bgcolor="#FFCC00">
      <td></td>
      <td>Quiz title</td>
      <td>Number of likes</td>
      <td>Quiz category</td>
      <td>Creation date</td>
      </tr>
      <div class="repeat-container">
      <?php 
	  if($totalRows_recommendations != 0){ do { ?>
      <tr>
      <!-- Check box, value of the check boxes are the quiz_id-->
      <td><input name="checkbox[]" type="checkbox" value="<?php echo $row_recommendations['quiz_id']; ?>" /></td>
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
      <!-- Number of takens-->
	  </tr>
        <?php } while ($row_recommendations = mysql_fetch_assoc($recommendations)); }else{ ?>
        <p>There are no latest quizzes!</p>
        <?php } ?>
        </div>
        </table>
    </div>
    
  </div>
  
</div>
<?php
mysql_free_result($recommendations);
mysql_free_result($popular);
?>
