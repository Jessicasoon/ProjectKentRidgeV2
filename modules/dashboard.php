<?php require('../modules/quizrooDB.php'); ?>
<?php require('../modules/variables.php');
require('../modules/quiz.php');

//---------ADDED BY YL on 2 sep for paging and query to retrieve total number of quizzes------------------
//---------ADDED BY YL on 14 sep for tabbing --------------------------------
$starting_quiz = $_GET['starting'];
$get_type = $_GET['sort'];

if($get_type == 0){ //for most popular
//retrieve total quizzes
$query_total = "SELECT COUNT(*) AS quiz_total FROM q_quizzes WHERE isPublished = 1";
$total = mysql_query($query_total, $quizroo) or die(mysql_error());
$totalRows_total = mysql_fetch_assoc($total);

// retrieve popular1 quizzes
$query_quizzes1 = sprintf("SELECT quiz_id, quiz_name, quiz_description, isRecommended, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 ORDER BY quiz_score DESC LIMIT %d, %d", $starting_quiz, $VAR_NUM_LISTINGS);
$quizzes1 = mysql_query($query_quizzes1, $quizroo) or die(mysql_error());
$row_quizzes1 = mysql_fetch_assoc($quizzes1);
$totalRows_quizzes1 = mysql_num_rows($quizzes1);

// retrieve popular2 quizzes
$query_quizzes2 = sprintf("SELECT quiz_id, quiz_name, quiz_description, isRecommended, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 ORDER BY quiz_score DESC LIMIT %d, %d", $starting_quiz+$VAR_NUM_LISTINGS, $VAR_NUM_LISTINGS);
$quizzes2 = mysql_query($query_quizzes2, $quizroo) or die(mysql_error());
$row_quizzes2 = mysql_fetch_assoc($quizzes2);
$totalRows_quizzes2 = mysql_num_rows($quizzes2);
}
else if($get_type == 1){ //for most recent
//retrieve total quizzes
$query_total = "SELECT COUNT(*) AS quiz_total FROM q_quizzes WHERE isPublished = 1";
$total = mysql_query($query_total, $quizroo) or die(mysql_error());
$totalRows_total = mysql_fetch_assoc($total);

//retrieve most recent1 quizzes
$query_quizzes1 = sprintf("SELECT DISTINCT quiz_id, quiz_name, quiz_description, isRecommended, quiz_picture, fk_quiz_cat, member_name, q_store_result.fk_member_id, cat_name, likes, dislikes, MAX(timestamp) FROM q_quizzes, q_quiz_cat, s_members, q_store_result WHERE fk_quiz_id = quiz_id AND member_id = q_quizzes.fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 GROUP BY quiz_id ORDER BY MAX(timestamp) DESC LIMIT %d, %d", $starting_quiz, $VAR_NUM_LISTINGS);
$quizzes1 = mysql_query($query_quizzes1, $quizroo) or die(mysql_error());
$row_quizzes1 = mysql_fetch_assoc($quizzes1);
$totalRows_quizzes1 = mysql_num_rows($quizzes1);

// retrieve most recent2 quizzes
$query_quizzes2 = sprintf("SELECT DISTINCT quiz_id, quiz_name, quiz_description, isRecommended, quiz_picture, fk_quiz_cat, member_name, q_store_result.fk_member_id, cat_name, likes, dislikes, MAX(timestamp) FROM q_quizzes, q_quiz_cat, s_members, q_store_result WHERE fk_quiz_id = quiz_id AND member_id = q_quizzes.fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 GROUP BY quiz_id ORDER BY MAX(timestamp) DESC LIMIT %d, %d", $starting_quiz+$VAR_NUM_LISTINGS, $VAR_NUM_LISTINGS);
$quizzes2 = mysql_query($query_quizzes2, $quizroo) or die(mysql_error());
$row_quizzes2 = mysql_fetch_assoc($quizzes2);
$totalRows_quizzes2 = mysql_num_rows($quizzes2);
}
else if($get_type == 2){ //for newly created
//retrieve total quizzes
$query_total = "SELECT COUNT(*) AS quiz_total FROM q_quizzes WHERE isPublished = 1";
$total = mysql_query($query_total, $quizroo) or die(mysql_error());
$totalRows_total = mysql_fetch_assoc($total);

// retrieve newly created1 quizzes
$query_quizzes1 = sprintf("SELECT quiz_id, quiz_name, quiz_description, isRecommended, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 ORDER BY creation_date DESC LIMIT %d, %d", $starting_quiz, $VAR_NUM_LISTINGS);
$quizzes1 = mysql_query($query_quizzes1, $quizroo) or die(mysql_error());
$row_quizzes1 = mysql_fetch_assoc($quizzes1);
$totalRows_quizzes1 = mysql_num_rows($quizzes1);

// retrieve newly created2 quizzes
$query_quizzes2 = sprintf("SELECT quiz_id, quiz_name, quiz_description, isRecommended, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 ORDER BY creation_date DESC LIMIT %d, %d", $starting_quiz+$VAR_NUM_LISTINGS, $VAR_NUM_LISTINGS);
$quizzes2 = mysql_query($query_quizzes2, $quizroo) or die(mysql_error());
$row_quizzes2 = mysql_fetch_assoc($quizzes2);
$totalRows_quizzes2 = mysql_num_rows($quizzes2);
}
else if($get_type == 3){ //for recommendation
	$sort_type = 'creation_date';
//retrieve total quizzes
$query_total = "SELECT COUNT(*) AS quiz_total FROM q_quizzes WHERE isPublished = 1 AND isRecommended = 1";
$total = mysql_query($query_total, $quizroo) or die(mysql_error());
$totalRows_total = mysql_fetch_assoc($total);

//retrieve recommended1 quizzes
$query_quizzes1 = sprintf("SELECT quiz_id, quiz_name, quiz_description, isRecommended, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 AND isRecommended = 1 ORDER BY creation_date DESC LIMIT %d, %d", $starting_quiz, $VAR_NUM_LISTINGS);
$quizzes1 = mysql_query($query_quizzes1, $quizroo) or die(mysql_error());
$row_quizzes1 = mysql_fetch_assoc($quizzes1);
$totalRows_quizzes1 = mysql_num_rows($quizzes1);

// retrieve recommended2 quizzes
$query_quizzes2 = sprintf("SELECT quiz_id, quiz_name, quiz_description, isRecommended, quiz_picture, fk_quiz_cat, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 AND isRecommended = 1 ORDER BY creation_date DESC LIMIT %d, %d", $starting_quiz+$VAR_NUM_LISTINGS, $VAR_NUM_LISTINGS);
$quizzes2 = mysql_query($query_quizzes2, $quizroo) or die(mysql_error());
$row_quizzes2 = mysql_fetch_assoc($quizzes2);
$totalRows_quizzes2 = mysql_num_rows($quizzes2);
}

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
  <br/>
  <div id="topics-bar" class="clear">
  <ul style="font-size:18px" > <!--------------TABBING LOGIC------------------->
  <!-- for aligning the tabs to center-->
  <li>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</li>
	  <li><?php if($get_type==0){echo "Most Popular";} else{?><a href ="../webroot/index.php?starting=0&sort=0">Most Popular</a><?php }?></li>
	  <li><?php if($get_type==1){echo "Most Recent";} else{?><a href ="../webroot/index.php?starting=0&sort=1">Most Recent</a><?php }?></li>
	  <li><?php if($get_type==2){echo "Newly Created";} else{?><a href ="../webroot/index.php?starting=0&sort=2">Newly Created</a><?php }?></li>
	  <li><?php if($get_type==4){echo "Recommendations";} else{?><a href ="../webroot/index.php?starting=0&sort=3">Recommendations</a><?php }?></li>
	</ul>
    </div>
  <div class="clear">
    <div id="recommendations" class="framePanel rounded left-right">
      <!--<h2>Popular1</h2>-->
      <div class="repeat-container">
      <?php if($totalRows_quizzes1 != 0){ do { ?>
        <div class="quiz_box clear">
          <h3>
          <!-- Modified on 13 Oct for greying out the taken quiz-->
          <a href="previewQuiz.php?id=<?php echo $row_quizzes1['quiz_id']; ?>" <?php $quiz = new Quiz($row_quizzes1['quiz_id']); if($quiz->hasTaken($member->id)){ echo 'style="color:#999"';}?>><?php echo $row_quizzes1['quiz_name']; ?></a>
          <!-- Modify on 6 Sep by Hien, to add the star for recommended quizzes-->
        <?php if ($row_quizzes1['isRecommended'] == 1){ ?>
        <img src="../webroot/img/5star.png" width="22" height="24" align="right"/>
        <?php } ?>
        <!-- end modification-->
          </h3>
          <div class="thumb_box">
            <a href="previewQuiz.php?id=<?php echo $row_quizzes1['quiz_id']; ?>"><img src="../quiz_images/imgcrop.php?w=90&amp;h=68&amp;f=<?php echo $row_quizzes1['quiz_picture']; ?>" alt="<?php echo $row_quizzes1['quiz_description']; ?>" width="90" height="68" border="0" title="<?php echo $row_quizzes1['quiz_description']; ?>" /></a></div>
          <div class="quiz_details">
            <p class="description" <?php $quiz = new Quiz($row_quizzes1['quiz_id']); if($quiz->hasTaken($member->id)){ echo 'style="color:#999"';}?>><?php echo substr($row_quizzes1['quiz_description'], 0, 110).((strlen($row_quizzes1['quiz_description']) < 110)? "" : "..."); ?></p>
            <p class="source">from <a href="topics.php?topic=<?php echo $row_quizzes1['fk_quiz_cat']; ?>"<?php $quiz = new Quiz($row_quizzes1['quiz_id']); if($quiz->hasTaken($member->id)){ echo 'style="color:#999"';}?>><?php echo $row_quizzes1['cat_name']; ?></a>  by <a href="viewMember.php?id=<?php echo $row_quizzes1['fk_member_id']; ?>"<?php $quiz = new Quiz($row_quizzes1['quiz_id']); if($quiz->hasTaken($member->id)){ echo 'style="color:#999"';}?>><?php echo $row_quizzes1['member_name']; ?></a></p>
			<p class="source"><?php //------------------type of quiz added by YL on 15oct------------------------
//***********************************************ADD BY LIEN************************************************//
		$queryMode = sprintf("SELECT display_mode FROM q_quizzes WHERE quiz_id = %d", $quiz->quiz_id);
		$resultMode =  mysql_query($queryMode, $quizroo) or die(mysql_error());
		$row_resultMode = mysql_fetch_assoc($resultMode);
		$resultforMode = array();
		$mode = "";
		do{
			$resultforMode[] = array($row_resultMode['display_mode']);
			if ($row_resultMode['display_mode'] == "multi_simple" || $row_resultMode['display_mode'] == "multi_accurate")
				$mode = "Personality";
			if ($row_resultMode['display_mode'] == "test_simple" || $row_resultMode['display_mode'] == "test_custom")
				$mode = "Test";					
		}while($row_resultMode = mysql_fetch_assoc($resultMode));

		//***********************************************END OF ADD BY LIEN************************************************//
			if($mode == "Personality" || $mode == "Test") {echo "Type: "; echo $mode;} ?></p>
			<?php if(!$GAME_ALLOW_DISLIKES){ if($row_quizzes1['likes'] > 0){ ?>
            <p class="rating"><span class="like"><?php echo $row_quizzes1['likes']; ?></span> <?php echo ($row_quizzes1['likes'] > 1) ? "people like" : "person likes"; ?> this</p>
			<?php }}else{ ?><p class="rating"><span class="like"><?php echo $row_quizzes1['likes']; ?></span> likes, <span class="dislike"><?php echo $row_quizzes1['dislikes']; ?></span> dislikes</p><?php } ?>
          </div>
        </div>
        <?php } while ($row_quizzes1 = mysql_fetch_assoc($quizzes1)); }else{ ?>
        <p>There are no quizzes here!</p>
        <?php } ?>
        </div>
    </div>
    <div id="popular" class="framePanel rounded left-right clear">
      <!--<h2>Popular2</h2>-->
      <div class="repeat-container">
      <?php if($totalRows_quizzes2 !=0 ){ do { ?>
        <div class="quiz_box clear">
          <h3>
        <a href="previewQuiz.php?id=<?php echo $row_quizzes2['quiz_id']; ?>"<?php $quiz = new Quiz($row_quizzes2['quiz_id']); if($quiz->hasTaken($member->id)){ echo 'style="color:#999"';}?>><?php echo $row_quizzes2['quiz_name']; ?></a>
        <!-- Modify on 6 Sep by Hien, to add the star for recommended quizzes-->
        <?php if ($row_quizzes2['isRecommended'] == 1){ ?>
        <img src="../webroot/img/5star.png" width="24" height="22" align="right"/>
        <?php } ?>
        <!-- end modification-->
        </h3>
          <div class="thumb_box">
            <a href="previewQuiz.php?id=<?php echo $row_quizzes2['quiz_id']; ?>"><img src="../quiz_images/imgcrop.php?w=90&amp;h=68&amp;f=<?php echo $row_quizzes2['quiz_picture']; ?>" alt="<?php echo $row_quizzes2['quiz_description']; ?>" width="90" height="68" border="0" title="<?php echo $row_quizzes2['quiz_description']; ?>" /></a></div>
          <div class="quiz_details">
            <p class="description"<?php $quiz = new Quiz($row_quizzes2['quiz_id']); if($quiz->hasTaken($member->id)){ echo 'style="color:#999"';}?>><?php echo substr($row_quizzes2['quiz_description'], 0, 120).((strlen($row_quizzes2['quiz_description']) < 120)? "" : "..."); ?></p>
            <p class="source">from <a href="topics.php?topic=<?php echo $row_quizzes2['fk_quiz_cat']; ?>"<?php $quiz = new Quiz($row_quizzes2['quiz_id']); if($quiz->hasTaken($member->id)){ echo 'style="color:#999"';}?>><?php echo $row_quizzes2['cat_name']; ?></a> by <a href="viewMember.php?id=<?php echo $row_quizzes2['fk_member_id']; ?>"<?php $quiz = new Quiz($row_quizzes2['quiz_id']); if($quiz->hasTaken($member->id)){ echo 'style="color:#999"';}?>><?php echo $row_quizzes2['member_name']; ?></a></p>
			<p class="source"><?php
//***********************************************ADD BY LIEN************************************************//
		$queryMode = sprintf("SELECT display_mode FROM q_quizzes WHERE quiz_id = %d", $quiz->quiz_id);
		$resultMode =  mysql_query($queryMode, $quizroo) or die(mysql_error());
		$row_resultMode = mysql_fetch_assoc($resultMode);
		$resultforMode = array();
		$mode = "";
		do{
			$resultforMode[] = array($row_resultMode['display_mode']);
			if ($row_resultMode['display_mode'] == "multi_simple" || $row_resultMode['display_mode'] == "multi_accurate")
				$mode = "Personality";
			if ($row_resultMode['display_mode'] == "test_simple" || $row_resultMode['display_mode'] == "test_custom")
				$mode = "Test";					
		}while($row_resultMode = mysql_fetch_assoc($resultMode));

		//***********************************************END OF ADD BY LIEN************************************************//
			if($mode == "Personality" || $mode == "Test") {echo "Type: "; echo $mode;} ?></p>			
			<?php if(!$GAME_ALLOW_DISLIKES){ if($row_quizzes2['likes'] > 0){ ?>
            <p class="rating"><span class="like"><?php echo $row_quizzes2['likes']; ?></span> <?php echo ($row_quizzes2['likes'] > 1) ? "people like" : "person likes"; ?> this</p>
			<?php }}else{ ?><p class="rating"><span class="like"><?php echo $row_quizzes2['likes']; ?></span> likes, <span class="dislike"><?php echo $row_quizzes2['dislikes']; ?></span> dislikes</p><?php } ?>
          </div>
        </div>
        <?php } while ($row_quizzes2 = mysql_fetch_assoc($quizzes2)); }else{ ?>
        <p>There are no quizzes here!</p>
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
	$current_page = $starting_quiz/($VAR_NUM_LISTINGS*2) + 1;
	if($pageNum>10){
		if($current_page>4){
			if($current_page+4 <= $pageNum){
				$start_page = $current_page - 4;
				$max_page = $current_page + 5;
			}
			else{
				$max_page = $pageNum;
				$start_page = $pageNum - 9;
			}
		}
		else{
			$start_page = 1;
			$max_page = 10;
		}
	}
	else{
		$start_page = 1;
		$max_page = $pageNum;
	}
	//first and previous
	if($current_page == 1){
		echo "First"; echo"&nbsp;&nbsp;"; echo "Previous"; echo"&nbsp;&nbsp;"; 
	}
	else { ?>
	<a href ="../webroot/index.php?starting=0&sort=<?php echo $get_type;?>"><?php echo "First"; echo "&nbsp;&nbsp;"; ?></a>	
	<a href ="../webroot/index.php?starting=<?php echo ($current_page-2)*$VAR_NUM_LISTINGS*2 ?>&sort=<?php echo $get_type;?>"><?php echo "Previous"; echo "&nbsp;&nbsp;"; ?></a>	
	<?php }//end else

	//number pages
	for($page=$start_page; $page<=$max_page; $page++) {
	if ($starting_quiz == ($page-1)*$VAR_NUM_LISTINGS*2){
		echo $page; echo "&nbsp;&nbsp;";
	}
	else { ?>
	<a href ="../webroot/index.php?starting=<?php echo ($page-1)*$VAR_NUM_LISTINGS*2 ?>&sort=<?php echo $get_type;?>"><?php echo $page; echo "&nbsp;&nbsp;"; ?></a> 
	<?php }}//end ifelse // end for loop ?>
	
	<?php //next and last
	if($current_page == $pageNum){
		echo "Next"; echo"&nbsp;&nbsp;"; echo "Last"; echo"&nbsp;&nbsp;"; 
	}
	else { ?>
	<a href ="../webroot/index.php?starting=<?php echo ($current_page)*$VAR_NUM_LISTINGS*2 ?>&sort=<?php echo $get_type;?>"><?php echo "Next"; echo "&nbsp;&nbsp;"; ?></a>	
	<a href ="../webroot/index.php?starting=<?php echo ($pageNum-1)*$VAR_NUM_LISTINGS*2 ?>&sort=<?php echo $get_type;?>"><?php echo "Last"; echo "&nbsp;&nbsp;"; ?></a>	
	<?php }//end else	?>
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
    <p>If you have any feedback on Quizroo, either on quiz content or quiz design, click on this &nbsp;<a href="../webroot/feedback.php" target="_blank"><img src="../webroot/img/feedback.png" alt="Give us your feedback" border="0" align="absmiddle"/></a></p>
    <p>Visit our <a href="http://www.facebook.com/apps/application.php?id=154849761223760" target="_blank">facebook page</a> for updates! Discuss what you like about Quizroo! <a href="http://www.twitter.com/quizroo" target="_blank"><img src="http://twitter-badges.s3.amazonaws.com/follow_us-b.png" alt="Follow Quizroo on Twitter" border="0" align="absmiddle"/></a></p>
    <iframe src="http://www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fapps%2Fapplication.php%3Fid%3D154849761223760&amp;layout=standard&amp;show_faces=false&amp;width=450&amp;action=like&amp;font&amp;colorscheme=light&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:450px; height:35px;" allowTransparency="true"></iframe>
    </div>
  </div>
</div>
<?php
mysql_free_result($quizzes1);
mysql_free_result($quizzes2);
?>
