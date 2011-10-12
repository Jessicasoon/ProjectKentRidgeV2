<?php require('../modules/quizrooDB.php'); ?>
<?php
$query_getQuizInfo = sprintf("SELECT quiz_id, quiz_name, quiz_description, quiz_picture, creation_date, s_members.member_name, q_quiz_cat.cat_name, (SELECT COUNT(question_id) FROM q_questions WHERE fk_quiz_id = %s) AS question_count FROM q_quizzes, s_members, q_quiz_cat WHERE quiz_id = %s AND s_members.member_id = q_quizzes.fk_member_id AND q_quiz_cat.cat_id = q_quizzes.fk_quiz_cat", GetSQLValueString($url_id, "int"),GetSQLValueString($url_id, "int"));
$getQuizInfo = mysql_query($query_getQuizInfo, $quizroo) or die(mysql_error());
$row_getQuizInfo = mysql_fetch_assoc($getQuizInfo);
$totalRows_getQuizInfo = mysql_num_rows($getQuizInfo);

$query_getQuizQuestions = sprintf("SELECT * FROM q_questions WHERE fk_quiz_id = %s", GetSQLValueString($url_id, "int"));
$getQuizQuestions = mysql_query($query_getQuizQuestions, $quizroo) or die(mysql_error());
$row_getQuizQuestions = mysql_fetch_assoc($getQuizQuestions);
$totalRows_getQuizQuestions = mysql_num_rows($getQuizQuestions);

$queryMode = sprintf("SELECT display_mode FROM q_quizzes WHERE quiz_id = %s", GetSQLValueString($url_id, "int"));
		$resultMode =  mysql_query($queryMode, $quizroo) or die(mysql_error());
		$row_resultMode = mysql_fetch_assoc($resultMode);
		$resultforMode = array();
		$mode = "";
		do{
			$resultforMode[] = array($row_resultMode['display_mode']);
			if ($row_resultMode['display_mode'] == "multi_simple")
				$mode = "simple";
			if ($row_resultMode['display_mode'] == "multi_accurate")
				$mode = "accurate";
			if ($row_resultMode['display_mode'] == "test_simple")
				$mode = "test_simple";	
			if ($row_resultMode['display_mode'] == "test_custom")
				$mode = "test_custom";				
		}while($row_resultMode = mysql_fetch_assoc($resultMode));

$question_count = 1;
$page_count = 1;
$total_pages = ceil( $row_getQuizInfo['question_count'] / 5) ;
$total_questions =  $row_getQuizInfo['question_count']; ?>

<script type="text/javascript">
var numQuestions = <?php echo $total_questions ?>;
</script>


<!-- <h4>Total Questions in this quiz:  <?php //echo $total_questions; ?> </h4> -->

<div id="takequiz-preamble" class="framePanel rounded">
  <h2>Take a quiz</h2>
  <div class="content-container">
  <p>You're now taking the quiz,<em> &quot;<?php echo $row_getQuizInfo['quiz_name']; ?>&quot;</em> by <?php echo $row_getQuizInfo['member_name']; ?>. You may stop taking the quiz anytime by navigating away from this page. No data will be collected unless you complete the quiz.</p>
  <div id="progress_panel">
      <div id="question_paging">
        <?php for($i = 0; $i < $total_pages; $i++) { ?>
        <a href="javascript:;" title="Jump to Page <?php echo ($i+1); ?>" rel="<?php echo ($i+1); ?>"><?php echo ($i+1); ?></a>
        <?php } ?>
      </div>
 
      <span id="final-bulb">&#10003;</span>
      <!--<p id="progress_text">Overall Progress (<span id="progress_percentage">0</span>%)</p>-->
      <div id="progress_bar">
        <div id="progress"></div>
      </div>
  </div>
  <div id="incomplete" class="rounded">
    <p>Pages marked with a white circle has question/s that  is/are not answered!</p>
    <p>Use the &quot;Previous&quot; and &quot;Next&quot; buttons to navigate between questions.</p>
  </div>
  </div>
</div>
<div id="takequiz-main">
  <form name="takeQuiz" id="takeQuiz" action="quiz_result.php?id=<?php echo $row_getQuizInfo['quiz_id']; ?>" method="post">
    <input type="hidden" name="quiz_id" value="<?php echo $row_getQuizInfo['quiz_id']; ?>" />
    <input type="hidden" name="logtime" id="logtime" value="<?php date_default_timezone_set("Asia/Singapore"); echo time(); ?>" />
    <div id="questionContainer">
      <div id="question_reel">
        <?php do {  
		    if ($total_questions > 5) {
				if (($total_questions - $question_count) < 5) $limit = $total_questions - $question_count + 1;
				else $limit = 5; 
			}
			else $limit = $total_questions;?>
            
           		        
			<div class="question_slide">
			<fieldset>  
			<?php
			for ($questionOnPage = 0; $questionOnPage < $limit; $questionOnPage ++) 
			{
			if ( $mode == "simple" ) 
			{ 	$query_getOptions = "SELECT * FROM q_options_multi WHERE fk_question_id = ".$row_getQuizQuestions['question_id']; 
				$getOptions = mysql_query($query_getOptions, $quizroo) or die(mysql_error()); 
				$row_getOptions = mysql_fetch_assoc($getOptions);
				$totalRows_getOptions = mysql_num_rows($getOptions);
			} 
			if ($mode == "accurate")
			{ 
				$query_getResultInfo = sprintf("SELECT result_id FROM q_results_multi WHERE fk_quiz_id = %s LIMIT 1", GetSQLValueString($url_id, "int"));
				$getResultInfo = mysql_query($query_getResultInfo, $quizroo) or die(mysql_error());
				$row_getResultInfo = mysql_fetch_assoc($getResultInfo);
				$totalRows_getResultInfo = mysql_num_rows($getResultInfo);
				
			//$query_getOptions = sprintf("SELECT * FROM q_options_multi WHERE fk_question_id = %d GROUP BY 'option' HAVING COUNT(DISTINCT 'option') > 0",$row_getQuizQuestions['question_id'] );	
				//$query_getOptions = sprintf(" SELECT Q.*  FROM q_options_multi Q JOIN (SELECT DISTINCT option_id as options FROM q_options_multi GROUP BY option_id) Q2 ON Q.option_id = Q2.options WHERE fk_question_id = %d GROUP BY option_id",$row_getQuizQuestions['question_id'] );
				$query_getOptions = sprintf("SELECT * FROM q_options_multi WHERE fk_question_id = %d AND fk_result_id = %d ",$row_getQuizQuestions['question_id'], $row_getResultInfo['result_id']);
				$getOptions = mysql_query($query_getOptions, $quizroo) or die(mysql_error()); 
				$row_getOptions = mysql_fetch_assoc($getOptions);
				$totalRows_getOptions = mysql_num_rows($getOptions); 
			} 
			if ( ($mode == "test_simple") || ($mode == "test_custom")) 
			{ 
				$query_getOptions = "SELECT * FROM q_options_test WHERE fk_question_id = ".$row_getQuizQuestions['question_id']; 
				$getOptions = mysql_query($query_getOptions, $quizroo) or die(mysql_error()); 
				$row_getOptions = mysql_fetch_assoc($getOptions);
				$totalRows_getOptions = mysql_num_rows($getOptions);
			}
			if ($mode == "")
			{ 
				$query_getOptions = "SELECT * FROM q_options WHERE fk_question_id = ".$row_getQuizQuestions['question_id']; 
				$getOptions = mysql_query($query_getOptions, $quizroo) or die(mysql_error()); 
				$row_getOptions = mysql_fetch_assoc($getOptions);
				$totalRows_getOptions = mysql_num_rows($getOptions);
			}
  			$option_count = 1;
		  ?>
 		
    
            
             <h4>Question<?php echo $question_count; ?> </h4>
            
            
 
            <?php if($row_getQuizQuestions['question_image'] != NULL){ ?>
            <span id="question-image"><img src="../quiz_images/imgcrop.php?w=500&h=375&f=<?php echo $row_getQuizQuestions['question_image']; ?>" width="500" height="375" /></span>
            <?php } ?>
                 
            <p><?php echo $row_getQuizQuestions['question']; ?></p>
            
            <?php if ($questionOnPage != 4) $row_getQuizQuestions = mysql_fetch_assoc($getQuizQuestions); ?>
            <table width="100%" border="0" cellpadding="5" cellspacing="0">
              <?php do { ?>
                <tr>
                  <th width="30" scope="row"><input type="radio" name="q<?php echo $question_count; ?>" id="q<?php echo $question_count; ?>o<?php echo $option_count; ?>" value="<?php echo $row_getOptions['option_id']; ?>" /></th>
                  <td><label for="q<?php echo $question_count; ?>o<?php echo $option_count; ?>"><?php echo $row_getOptions['option']; ?></label></td>
                </tr>
                <?php $option_count++; } while ($row_getOptions = mysql_fetch_assoc($getOptions)); ?>
                <?php $question_count++; mysql_free_result($getOptions); ?>
            </table>
           <?php } ?>
           
             
            <table width="95%" border="0" align="center" cellpadding="5" cellspacing="0" id="question_navigation">
              <?php if($page_count != $total_pages){ if($page_count == 1){ ?>
              <tr>
                <td align="left" scope="row">&nbsp;</td>
                <td align="right"><input name="nextBtn<?php echo $question_count; ?>" type="button" class="styleBtn" id="nextBtn<?php echo $question_count; ?>" value="Next" /></td>
              </tr>
              <?php }else{ ?>
              <tr>
                <td align="left" scope="row"><input name="prevBtn<?php echo $question_count; ?>" type="button" class="styleBtn" id="prevBtn<?php echo $question_count; ?>" value="Previous" /></td>
                <td align="right"><input name="nextBtn<?php echo $question_count; ?>" type="button" class="styleBtn" id="nextBtn<?php echo $question_count; ?>" value="Next" /></td>
              </tr>
              <?php }}else{ if ($total_pages == 1) {?>
               <tr>
               <td align="left" scope="row">&nbsp;</td>
               <td align="right"><input name="finishQuiz" type="submit" class="btnDisabled" id="finishQuiz" value="Complete Quiz" /></td>
              </tr>
              <?php } else { ?>
              <tr>
                <td align="left" scope="row"><input name="prevBtn<?php echo $question_count; ?>" type="button" class="styleBtn" id="prevBtn<?php echo $question_count; ?>" value="Previous" /></td>
                <td align="right"><input name="finishQuiz" type="submit" class="btnDisabled" id="finishQuiz" value="Complete Quiz" /></td>
              </tr>
              <?php }} ?>
            </table>
          </fieldset>
        </div>
        <?php 
		$page_count ++;
		
		} while ($row_getQuizQuestions = mysql_fetch_assoc($getQuizQuestions) ); ?>
      </div>
    </div>
  </form>
</div>
<?php
mysql_free_result($getQuizInfo);
mysql_free_result($getQuizQuestions);
?>
