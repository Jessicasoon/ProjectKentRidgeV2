<?php
require('../modules/quizrooDB.php');
require('../modules/variables.php');

// hack for checking topic IDs
if(isset($_GET['topic'])){
	if($_GET['topic'] >= 1 && $_GET['topic'] <= 8){
		$topic = $_GET['topic'];
	}else{
		$topic = 1;
	}
}

//--------ADDED BY YL on 2 sep for paging and query to retrieve total number of quizzes--------------
$starting_quiz = $_GET['starting'];

//retrieve total quizzes
$query_total = sprintf("SELECT COUNT(*) AS quiz_total FROM q_quizzes WHERE isPublished = 1 AND fk_quiz_cat = %d", GetSQLValueString($topic, "int"));
$total = mysql_query($query_total, $quizroo) or die(mysql_error());
$totalRows_total = mysql_fetch_assoc($total);

// retrieve recommended quizzes
$query_recommendations = sprintf("SELECT quiz_id, quiz_name, quiz_description, quiz_picture, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 AND fk_quiz_cat = %d ORDER BY quiz_score DESC LIMIT %d, %d", GetSQLValueString($topic, "int"), $starting_quiz, $VAR_NUM_LISTINGS);
$recommendations = mysql_query($query_recommendations, $quizroo) or die(mysql_error());
$row_recommendations = mysql_fetch_assoc($recommendations);
$totalRows_recommendations = mysql_num_rows($recommendations);

// retrieve popular quizzes
$query_popular = sprintf("SELECT quiz_id, quiz_name, quiz_description, quiz_picture, member_name, fk_member_id, cat_name, likes, dislikes FROM q_quizzes, q_quiz_cat, s_members WHERE member_id = fk_member_id AND cat_id = fk_quiz_cat AND isPublished = 1 AND fk_quiz_cat = %d ORDER BY quiz_score DESC LIMIT %d, %d", GetSQLValueString($topic, "int"), $starting_quiz+$VAR_NUM_LISTINGS, $VAR_NUM_LISTINGS);
$popular = mysql_query($query_popular, $quizroo) or die(mysql_error());
$row_popular = mysql_fetch_assoc($popular);
$totalRows_popular = mysql_num_rows($popular);


// fetch the topic information
$query_getTopics = sprintf("SELECT * FROM q_quiz_cat WHERE cat_id = %d", GetSQLValueString($topic, "int"));
$getTopics = mysql_query($query_getTopics, $quizroo) or die(mysql_error());
$row_getTopics = mysql_fetch_assoc($getTopics);
$totalRows_getTopics = mysql_num_rows($getTopics);
?>
<div id="dashboard-container">
  <div id="topic-preamble" class="frame rounded">
    <h2><?php echo $row_getTopics['cat_name']; ?></h2>
	<p><?php echo $row_getTopics['cat_desc']; ?></p>
  </div>
  <div class="clear">
    <div id="recommendations" class="framePanel rounded left-right">
      <h2>Latest</h2>
      <div class="repeat-container">
      <?php if($totalRows_recommendations != 0){ do { ?>
        <div class="quiz_box clear">
			<h3><a href="previewQuiz.php?id=<?php echo $row_recommendations['quiz_id']; ?>"><?php echo $row_recommendations['quiz_name']; ?></a></h3>
				<div class="thumb_box">
            <!--<div class="quiz_rating">Overlay</div>-->
            <a href="previewQuiz.php?id=<?php echo $row_recommendations['quiz_id']; ?>"><img src="../quiz_images/imgcrop.php?w=90&amp;h=68&amp;f=<?php echo $row_recommendations['quiz_picture']; ?>" alt="<?php echo $row_recommendations['quiz_description']; ?>" width="90" height="68" border="0" title="<?php echo $row_recommendations['quiz_description']; ?>" /></a></div>
          <div class="quiz_details">
            <p class="description"><?php echo substr($row_recommendations['quiz_description'], 0, 120).((strlen($row_recommendations['quiz_description']) < 120)? "" : "..."); ?></p>
            <p class="source">by <a href="viewMember.php?id=<?php echo $row_recommendations['fk_member_id']; ?>"><?php echo $row_recommendations['member_name']; ?></a></p>
			<?php if(!$GAME_ALLOW_DISLIKES){ if($row_recommendations['likes'] > 0){ ?>
            <p class="rating"><span class="like"><?php echo $row_recommendations['likes']; ?></span> <?php echo ($row_recommendations['likes'] > 1) ? "people like" : "person likes"; ?> this</p>
			<?php }}else{ ?><p class="rating"><span class="like"><?php echo $row_recommendations['likes']; ?></span> likes, <span class="dislike"><?php echo $row_recommendations['dislikes']; ?></span> dislikes</p><?php } ?>
          </div>
        </div>
        <?php } while ($row_recommendations = mysql_fetch_assoc($recommendations)); }else{ ?>
        <p>There are no latest quizzes for this topic!</p>
        <?php } ?>
        </div>
    </div>
    <div id="popular" class="framePanel rounded left-right clear">
      <h2>Popular</h2>
      <div class="repeat-container">
      <?php if($totalRows_popular !=0 ){ do { ?>
        <div class="quiz_box clear">
          <h3><a href="previewQuiz.php?id=<?php echo $row_popular['quiz_id']; ?>"><?php echo $row_popular['quiz_name']; ?></a></h3>
          <div class="thumb_box">
            <a href="previewQuiz.php?id=<?php echo $row_popular['quiz_id']; ?>"><img src="../quiz_images/imgcrop.php?w=90&amp;h=68&amp;f=<?php echo $row_popular['quiz_picture']; ?>" alt="<?php echo $row_popular['quiz_description']; ?>" width="90" height="68" border="0" title="<?php echo $row_popular['quiz_description']; ?>" /></a></div>
          <div class="quiz_details">
            <p class="description"><?php echo substr($row_popular['quiz_description'], 0, 120).((strlen($row_popular['quiz_description']) < 120)? "" : "..."); ?></p>
            <p class="source">by <a href="viewMember.php?id=<?php echo $row_popular['fk_member_id']; ?>"><?php echo $row_popular['member_name']; ?></a>
			<?php if(!$GAME_ALLOW_DISLIKES){ if($row_popular['likes'] > 0){ ?>
            <p class="rating"><span class="like"><?php echo $row_popular['likes']; ?></span> <?php echo ($row_popular['likes'] > 1) ? "people like" : "person likes"; ?> this</p>
			<?php }}else{ ?><p class="rating"><span class="like"><?php echo $row_popular['likes']; ?></span> likes, <span class="dislike"><?php echo $row_popular['dislikes']; ?></span> dislikes</p><?php } ?>
          </div>
        </div>
        <?php } while ($row_popular = mysql_fetch_assoc($popular)); }else{ ?>
        <p>There are no popular quizzes for this topic!</p>
        <?php } ?>
        </div>
    </div>
  </div>
  <!-------------ADDED BY YL on 2sep for paging logic and display------------->
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
	<a href ="../webroot/topics.php?topic=<?php echo $topic; ?>&starting=0"><?php echo "First"; echo "&nbsp;&nbsp;"; ?></a>	
	<a href ="../webroot/topics.php?topic=<?php echo $topic; ?>&starting=<?php echo ($current_page-2)*$VAR_NUM_LISTINGS*2 ?>"><?php echo "Previous"; echo "&nbsp;&nbsp;"; ?></a>	
	<?php }//end else

	//number pages
	for($page=$start_page; $page<=$max_page; $page++) {
	if ($starting_quiz == ($page-1)*$VAR_NUM_LISTINGS*2){
		echo $page; echo "&nbsp;&nbsp;";
	}
	else { ?>
	<a href ="../webroot/topics.php?topic=<?php echo $topic; ?>&starting=<?php echo ($page-1)*$VAR_NUM_LISTINGS*2 ?>"><?php echo $page; echo "&nbsp;&nbsp;"; ?></a> 
	<?php }}//end ifelse // end for loop ?>
	
	<?php //next and last
	if($current_page == $pageNum){
		echo "Next"; echo"&nbsp;&nbsp;"; echo "Last"; echo"&nbsp;&nbsp;"; 
	}
	else { ?>
	<a href ="../webroot/topics.php?topic=<?php echo $topic; ?>&starting=<?php echo ($current_page)*$VAR_NUM_LISTINGS*2 ?>"><?php echo "Next"; echo "&nbsp;&nbsp;"; ?></a>	
	<a href ="../webroot/topics.php?topic=<?php echo $topic; ?>&starting=<?php echo ($pageNum-1)*$VAR_NUM_LISTINGS*2 ?>"><?php echo "Last"; echo "&nbsp;&nbsp;"; ?></a>	
	<?php }//end else	?>
	</p>
  </div>    
</div>
<?php
mysql_free_result($recommendations);
mysql_free_result($popular);
?>
