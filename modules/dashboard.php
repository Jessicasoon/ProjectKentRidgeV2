<?php require('../modules/quizrooDB.php'); ?>
<?php require('../modules/variables.php');
/**************************************
// retrieve recommended quizzes
$query_recommendations = sprintf("SELECT quiz_id, quiz_name, quiz_description, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 ORDER BY creation_date DESC LIMIT 0, %d", $VAR_NUM_LISTINGS);
$recommendations = mysql_query($query_recommendations, $quizroo) or die(mysql_error());
$row_recommendations = mysql_fetch_assoc($recommendations);
$totalRows_recommendations = mysql_num_rows($recommendations);

// retrieve popular quizzes
$query_popular = sprintf("SELECT * FROM (SELECT quiz_id, quiz_name, quiz_description, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes, quiz_score * (IF(likes > 0, likes, 0.5)) AS rankscore FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 ORDER BY rankscore DESC LIMIT 0, %d) t ORDER BY RAND() LIMIT 0, %d", $VAR_NUM_POPULAR_POOL, $VAR_NUM_LISTINGS);
$popular = mysql_query($query_popular, $quizroo) or die(mysql_error());
$row_popular = mysql_fetch_assoc($popular);
$totalRows_popular = mysql_num_rows($popular);
**********************************************/

//---------ADDED BY YL on 2 sep for paging and query to retrieve total number of quizzes------------------
$starting_quiz = $_GET['starting'];

//retrieve total quizzes
$query_total = "SELECT COUNT(*) AS quiz_total FROM q_quizzes WHERE isPublished = 1";
$total = mysql_query($query_total, $quizroo) or die(mysql_error());
$totalRows_total = mysql_fetch_assoc($total);

// retrieve popular1 quizzes
$query_popular1 = sprintf("SELECT quiz_id, quiz_name, quiz_description, isRecommended, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 ORDER BY quiz_score DESC LIMIT %d, %d", $starting_quiz, $VAR_NUM_LISTINGS);
$popular1 = mysql_query($query_popular1, $quizroo) or die(mysql_error());
$row_popular1 = mysql_fetch_assoc($popular1);
$totalRows_popular1 = mysql_num_rows($popular1);

// retrieve popular2 quizzes
$query_popular2 = sprintf("SELECT quiz_id, quiz_name, quiz_description, isRecommended, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 ORDER BY quiz_score DESC LIMIT %d, %d", $starting_quiz+7, $VAR_NUM_LISTINGS);
$popular2 = mysql_query($query_popular2, $quizroo) or die(mysql_error());
$row_popular2 = mysql_fetch_assoc($popular2);
$totalRows_popular2 = mysql_num_rows($popular2);

?>

<div id="dashboard-container">
  <?php if($VAR_SHOW_RECENT){ ?>
	<?php include("../modules/recentActivity.php");?>
  <?php } ?>
  <?php if($VAR_SYSTEM_MAINTENANCE){ ?>
  <div class="framePanel rounded">
  	<h2 class="panelHeader">Maintenance Mode is ON</h2>
    <div class="content-container">
    <p>Remember to turn it off after carrying out the required maintenance!</p>
    </div>
  </div>
  <?php } ?>
  <div class="tab">
  <p> TEST</p>
  </div>
  <div class="clear">
    <div id="popular1" class="framePanel rounded left-right">
      <h2>Popular1</h2>
      <div class="repeat-container">
      <?php if($totalRows_popular1 != 0){ do { ?>
        <div class="quiz_box clear">
          <h3>
          <a href="previewQuiz.php?id=<?php echo $row_popular1['quiz_id']; ?>"><?php echo $row_popular1['quiz_name']; ?></a>
          <!-- Modify on 6 Sep by Hien, to add the star for recommended quizzes-->
        <?php if ($row_popular1['isRecommended'] == 1){ ?>
        <img src="../webroot/img/5star.png" width="22" height="24" align="right"/>
        <?php } ?>
        <!-- end modification-->
          </h3>
          <div class="thumb_box">
            <a href="previewQuiz.php?id=<?php echo $row_popular1['quiz_id']; ?>"><img src="../quiz_images/imgcrop.php?w=90&amp;h=68&amp;f=<?php echo $row_popular1['quiz_picture']; ?>" alt="<?php echo $row_popular1['quiz_description']; ?>" width="90" height="68" border="0" title="<?php echo $row_popular1['quiz_description']; ?>" /></a></div>
          <div class="quiz_details">
            <p class="description"><?php echo substr($row_popular1['quiz_description'], 0, 110).((strlen($row_popular1['quiz_description']) < 110)? "" : "..."); ?></p>
            <p class="source">from <a href="topics.php?topic=<?php echo $row_popular1['fk_quiz_cat']; ?>"><?php echo $row_popular1['cat_name']; ?></a>  by <a href="viewMember.php?id=<?php echo $row_popular1['fk_member_id']; ?>"><?php echo $row_popular1['member_name']; ?></a></p>
			<?php if(!$GAME_ALLOW_DISLIKES){ if($row_popular1['likes'] > 0){ ?>
            <p class="rating"><span class="like"><?php echo $row_popular1['likes']; ?></span> <?php echo ($row_popular1['likes'] > 1) ? "people like" : "person likes"; ?> this</p>
			<?php }}else{ ?><p class="rating"><span class="like"><?php echo $row_popular1['likes']; ?></span> likes, <span class="dislike"><?php echo $row_popular1['dislikes']; ?></span> dislikes</p><?php } ?>
          </div>
        </div>
        <?php } while ($row_popular1 = mysql_fetch_assoc($popular1)); }else{ ?>
        <p>There are no latest quizzes!</p>
        <?php } ?>
        </div>
    </div>
    <div id="popular2" class="framePanel rounded left-right clear">
      <h2>Popular2</h2>
      <div class="repeat-container">
      <?php if($totalRows_popular2 !=0 ){ do { ?>
        <div class="quiz_box clear">
        <h3>
        <a href="previewQuiz.php?id=<?php echo $row_popular2['quiz_id']; ?>"><?php echo $row_popular2['quiz_name']; ?></a>
        <!-- Modify on 6 Sep by Hien, to add the star for recommended quizzes-->
        <?php if ($row_popular2['isRecommended'] == 1){ ?>
        <img src="../webroot/img/5star.png" width="24" height="22" align="right"/>
        <?php } ?>
        <!-- end modification-->
        </h3>
          <div class="thumb_box">
            <a href="previewQuiz.php?id=<?php echo $row_popular2['quiz_id']; ?>"><img src="../quiz_images/imgcrop.php?w=90&amp;h=68&amp;f=<?php echo $row_popular2['quiz_picture']; ?>" alt="<?php echo $row_popular2['quiz_description']; ?>" width="90" height="68" border="0" title="<?php echo $row_popular2['quiz_description']; ?>" /></a></div>
          <div class="quiz_details">
            <p class="description"><?php echo substr($row_popular2['quiz_description'], 0, 120).((strlen($row_popular2['quiz_description']) < 120)? "" : "..."); ?></p>
            <p class="source">from <a href="topics.php?topic=<?php echo $row_popular2['fk_quiz_cat']; ?>"><?php echo $row_popular2['cat_name']; ?></a> by <a href="viewMember.php?id=<?php echo $row_popular2['fk_member_id']; ?>"><?php echo $row_popular2['member_name']; ?></a>
			<?php if(!$GAME_ALLOW_DISLIKES){ if($row_popular2['likes'] > 0){ ?>
            <p class="rating"><span class="like"><?php echo $row_popular2['likes']; ?></span> <?php echo ($row_popular2['likes'] > 1) ? "people like" : "person likes"; ?> this</p>
			<?php }}else{ ?><p class="rating"><span class="like"><?php echo $row_popular2['likes']; ?></span> likes, <span class="dislike"><?php echo $row_popular2['dislikes']; ?></span> dislikes</p><?php } ?>
          </div>
        </div>
        <?php } while ($row_popular2 = mysql_fetch_assoc($popular2)); }else{ ?>
        <p>There are no popular quizzes for this topic!</p>
        <?php } ?>
        </div>
    </div>
  </div>
  <!------------ADDED BY YL on 2sep for paging logic and display----------------->
  <div id="pages" class="page-container">
	<p><?php 
	$pageNum = $totalRows_total['quiz_total']/($VAR_NUM_LISTINGS*2);
	if($totalRows_total['quiz_total']%($VAR_NUM_LISTINGS*2) > 0){
		$pageNum = floor($pageNum)+1;
	}
	for($page=1; $page<=$pageNum; $page++) {
	if ($starting_quiz == ($page-1)*$VAR_NUM_LISTINGS*2){
		echo $page; echo "&nbsp;&nbsp;";
	}
	else { ?>
	<a href ="../webroot/index.php?starting=<?php echo ($page-1)*$VAR_NUM_LISTINGS*2 ?>"><?php echo $page; echo "&nbsp;&nbsp;"; ?></a> 
	<?php }}//end ifelse // end for loop ?>
	</p>
  </div>
  
  <!-- Modified on 26jul for moving the topic bar to the bottom-->
  <div>
  <br/>
	<?php include('../modules/topicBarFooter.php')?>
  </div>
  <!-- end modification-->
  
  <div id="social" class="framePanel rounded">
    <h2>Social</h2>
    <div class="content-container">
    <p>Visit our <a href="http://www.facebook.com/apps/application.php?id=154849761223760" target="_blank">facebook page</a> for updates! Discuss what you like about Quizroo! <a href="http://www.twitter.com/quizroo" target="_blank"><img src="http://twitter-badges.s3.amazonaws.com/follow_us-b.png" alt="Follow Quizroo on Twitter" border="0" align="absmiddle"/></a></p>
    <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fapps%2Fapplication.php%3Fid%3D154849761223760&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;font&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe>
    </div>
  </div>
</div>
<?php
mysql_free_result($popular1);
mysql_free_result($popular2);
?>
