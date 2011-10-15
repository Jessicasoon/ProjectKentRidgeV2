<?php require("quizrooDB.php");
// prepare result options
$querySQL = "SELECT result_id, result_title FROM q_results_multi WHERE fk_quiz_id = ".GetSQLValueString($_GET['id'], "int");
$resultID = mysql_query($querySQL, $quizroo) or die(mysql_error());
$row_resultID = mysql_fetch_assoc($resultID);
$total_results = mysql_num_rows($resultID);

$results = array();

do{
	$results[] = array($row_resultID['result_id'], $row_resultID['result_title']);
}while($row_resultID = mysql_fetch_assoc($resultID));

mysql_free_result($resultID);

//***********************************************ADD BY LIEN************************************************//
$queryMode = sprintf("SELECT display_mode FROM q_quizzes WHERE quiz_id = %d", GetSQLValueString($_GET['id'], "int"));
$resultMode =  mysql_query($queryMode, $quizroo) or die(mysql_error());
$row_resultMode = mysql_fetch_assoc($resultMode);
$resultforMode = array();
$mode = "";
do{
	$resultforMode[] = array($row_resultMode['display_mode']);
	//echo $row_resultMode['display_mode']; //debugging purpose
	if ($row_resultMode['display_mode'] == "multi_simple")
		$mode = "simple";
	if ($row_resultMode['display_mode'] == "multi_accurate")
		$mode = "accurate";
}while($row_resultMode = mysql_fetch_assoc($resultMode));

//***********************************************END OF ADD BY LIEN************************************************//

// check what to do
if(isset($_GET['load'])){
	require('quizrooDB.php');
	
	$query = sprintf("SELECT question_id, question, question_image, question_order FROM q_questions WHERE fk_quiz_id = %d", GetSQLValueString($_GET['id'], "int"));
	$getQuery = mysql_query($query, $quizroo) or die(mysql_error());
	$row_getQuery = mysql_fetch_assoc($getQuery);
	$totalRows_getQuery = mysql_num_rows($getQuery);
	
	$question = 0;
	
	if ($mode == "simple"){ //FOR MODIFY SIMPLE MODE
	if($totalRows_getQuery > 0){
		do{
?>
<div id="q<?php echo $question; ?>" class="questionWidget">
<input type="hidden" name="uq<?php echo $question; ?>" id="uq<?php echo $question; ?>" value="<?php echo $row_getQuery['question_id']; ?>" />
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th width="25" scope="row"><a href="javascript:;" onclick="QuizQuestionMulti.remove(<?php echo $question; ?>);"><img src="img/delete.png" alt="" width="16" height="16" border="0" align="absmiddle" title="Remove" /></a></th>
    <th width="80" scope="row"><label for="question_<?php echo $question; ?>">Question</label></th>
    <td><span id="sprytextfield-q<?php echo $question; ?>">
      <input type="text" name="question_<?php echo $question; ?>" id="question_<?php echo $question; ?>" class="questionField" value="<?php echo $row_getQuery['question']; ?>" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
</table>
<div id="optionContainer_<?php echo $question; ?>">
  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr class="optionTable">
      <th width="25">&nbsp;</th>
      <th width="80">&nbsp;</th>
      <th align="left">Option Value</th>
      <th width="150" align="center">Contributes to</th>
      <!--<th width="100" align="center">Weightage</th>-->
    </tr>
  </table>
    <?php 
	$queryOption = sprintf("SELECT `option_id`, `option`, `fk_result_id`, `option_weightage` FROM q_options_multi WHERE fk_question_id = %d ORDER BY option_id", GetSQLValueString($row_getQuery['question_id'], "int"));
	$getOption = mysql_query($queryOption, $quizroo) or die(mysql_error());
	$row_getOption = mysql_fetch_assoc($getOption);
	$totalRows_getOption = mysql_num_rows($getOption);
	
	$option = 0;
	
	if($totalRows_getOption > 0){
		do{
	?>
    <div id="cq<?php echo $question; ?>o<?php echo $option; ?>">
    <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <th width="25" scope="row"><input type="hidden" name="uq<?php echo $question; ?>o<?php echo $option; ?>" id="uq<?php echo $question; ?>o<?php echo $option; ?>" value="<?php echo $row_getOption['option_id']; ?>" /><a href="javascript:;" onclick="QuizQuestionMulti.removeOption(<?php echo $question; ?>, <?php echo $option; ?>);"><img src="img/delete.png" width="16" height="16" border="0" align="absmiddle" title="Remove" /></a></th>
      <th width="80" scope="row"><label for="q<?php echo $question; ?>o<?php echo $option; ?>" class="optionWidget-<?php echo $question; ?>">Option</label></th>
      <td><span id="sprytextfield-q<?php echo $question; ?>o<?php echo $option; ?>" class="sprytextfield">
        <input name="q<?php echo $question; ?>o<?php echo $option; ?>" type="text" class="optionField" id="q<?php echo $question; ?>o<?php echo $option; ?>" value="<?php echo $row_getOption['option']; ?>" />
        <span class="textfieldRequiredMsg">Enter a value for this option!</span></span></td>
      <td width="150"><select name="q<?php echo $question; ?>r<?php echo $option; ?>" class="optionSelect" id="q<?php echo $question; ?>r<?php echo $option; ?>">
          <?php foreach($results as $item){ ?>
          <option value="<?php echo $item[0]; ?>" <?php if($item[0] == $row_getOption['fk_result_id']){ echo "selected"; }; ?>><?php echo $item[1]; ?></option>
          <?php } ?>
      </select></td>
      <!--<td width="100"><select name="q<?php //echo $question; ?>w<?php //echo $option; ?>" id="q<?php //echo $question; ?>w<?php //echo $option; ?>">
          <option value="1" <?php //if(1 == $row_getOption['option_weightage']){ echo "selected"; }; ?>>A little</option>
          <option value="2" <?php //if(2 == $row_getOption['option_weightage']){ echo "selected"; }; ?>>Somewhat</option>
          <option value="3" <?php //if(3 == $row_getOption['option_weightage']){ echo "selected"; }; ?>>A lot</option>
      </select></td>-->
    </tr>
    </table>
    </div>
    <?php $option++; }while($row_getOption = mysql_fetch_assoc($getOption)); }?>
</div>
  <table border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <th valign="top" scope="row"><input type="button" name="addOptionBtn<?php echo $question; ?>" id="addOptionBtn<?php echo $question; ?>" value="Add new option" onClick="QuizQuestionMulti.addOption(<?php echo $question; ?>)" /></th>
    </tr>
    <tr>
      <td valign="top" class="desc" scope="row">Create a new option for this question</td>
    </tr>
  </table>
</div>
<?php 		$question++;
		}while($row_getQuery = mysql_fetch_assoc($getQuery));
	}
	} 
	if ($mode == "accurate"){ //FOR MODIFY ACCURATE MODE
	if($totalRows_getQuery > 0){
		do{
?>
<div id="q<?php echo $question; ?>" class="questionWidget">
<input type="hidden" name="uq<?php echo $question; ?>" id="uq<?php echo $question; ?>" value="<?php echo $row_getQuery['question_id']; ?>" />
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th width="25" scope="row"><a href="javascript:;" onclick="QuizQuestionMulti.remove(<?php echo $question; ?>);"><img src="img/delete.png" alt="" width="16" height="16" border="0" align="absmiddle" title="Remove" /></a></th>
    <th width="80" scope="row"><label for="question_<?php echo $question; ?>">Question</label></th>
    <td><span id="sprytextfield-q<?php echo $question; ?>">
      <input type="text" name="question_<?php echo $question; ?>" id="question_<?php echo $question; ?>" class="questionField" value="<?php echo $row_getQuery['question']; ?>" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
</table>
<div id="optionContainer_<?php echo $question; ?>">
  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr class="optionTable">
      <th width="25">&nbsp;</th>
      <th width="80">&nbsp;</th>
      <th align="left">Option Value</th>
      <th width="150" align="center">Contributes to</th>
      <th width="100" align="center">Weightage</th>
    </tr>
  </table>
    <?php 
	$queryOption = sprintf("SELECT `option_id`, `option`, `fk_result_id`, `option_weightage`, `result_title` FROM q_options_multi, q_results_multi WHERE fk_question_id = %d and result_id = fk_result_id", GetSQLValueString($row_getQuery['question_id'], "int"));
	$getOption = mysql_query($queryOption, $quizroo) or die(mysql_error());
	$row_getOption = mysql_fetch_assoc($getOption);
	$totalRows_getOption = mysql_num_rows($getOption); // get the number of records return from query
	
	/* loop options */
	// get list of unique options
	$queryOptions = sprintf("SELECT DISTINCT `option` FROM q_options_multi WHERE fk_question_id = %d", GetSQLValueString($row_getQuery['question_id'], "int"));
	// execute query options
	$resultOptions = mysql_query($queryOptions, $quizroo) or die(mysql_error());
	// define cursor to loop throught options
	$rowOptions = mysql_fetch_assoc($resultOptions);
	// count total rows
	$totalRowOptions = mysql_num_rows($resultOptions);
	
	// loop through the options
	for ($i = 0; $i < $totalRowOptions; $i++)
	{
		?>
        <!--print the options to the screen-->
		<div id="cq<?php echo $question; ?>o<?php echo $i; ?>">
        
                <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
                <tr>
      <!-- Modified on 15 Oct, for checking if the option is the 1st and 2nd, cant delete-->
      <th width="25" scope="row">
       <a href="javascript:;" onclick="QuizQuestionMulti.removeOption(<?php echo $row_getQuery['question_id']; ?>, <?php echo $question; ?>, <?php echo $i; ?>);"><img src="img/delete.png" width="16" height="16" border="0" align="absmiddle" title="Remove" /></a></th>
      <th width="80" scope="row"><label for="q<?php echo $question; ?>o<?php echo $i; ?>" class="optionWidget-<?php echo $question; ?>">Option</label></th>
      <td><span id="sprytextfield-q<?php echo $question; ?>o<?php echo $i; ?>" class="sprytextfield">  
        <input name="q<?php echo $question; ?>o<?php echo $i; ?>" type="text" class="optionField" id="q<?php echo $question; ?>o<?php echo $i; ?>" value="<?php echo $rowOptions['option']; ?>" />
        <span class="textfieldRequiredMsg">Enter a value for this option!</span></span></td>
        
        <?php
		/* loop results */
		// get list of unique results
		$queryResults = sprintf("SELECT DISTINCT `fk_result_id`, `result_title`, `option_weightage` FROM q_options_multi, q_results_multi WHERE fk_question_id = %d AND `option` = '%s' AND fk_result_id = result_id", GetSQLValueString($row_getQuery['question_id'], "int"), $rowOptions['option']);
		// execute query options
		$resultResults = mysql_query($queryResults, $quizroo) or die(mysql_error());
		// define cursor to loop throught options
		$rowResults = mysql_fetch_assoc($resultResults);
		// count total rows
		$totalRowResults = mysql_num_rows($resultResults);
		
       	// loop through the results of each option
		for ($j = 0; $j < $totalRowResults; $j++)
		{
			?>
            <!-- Modified by Hien on 13 Oct for formatting the table-->
                    <?php if ($j > 0){ ?>
                    <tr class = "optionTable">
                              <th width="25">&nbsp;</th>
                              <th width="80">&nbsp;</th>
                              <th align="left">&nbsp;</th>
                    <?php } ?>
            <input type="hidden" name="q<?php echo $question; ?>o<?php echo $i; ?>r<?php echo $j; ?>" id="q<?php echo $question; ?>o<?php echo $i; ?>r<?php echo $j; ?>" value="<?php echo $rowResults['fk_result_id']; ?>" />
      
            <td width="150" align="center"><?php echo $rowResults['result_title']; ?></td>
          <td width="100"><select name="q<?php echo $question; ?>o<?php echo $i; ?>w<?php echo $j; ?>" id="q<?php echo $question; ?>o<?php echo $i; ?>w<?php echo $j; ?>">
              <option value="0"<?php if(0 == $rowResults['option_weightage']){ echo 'selected = "selected"'; }; ?>>&nbsp;&nbsp;&nbsp;&nbsp;0&nbsp;&nbsp;&nbsp;</option>
              <option value="1"<?php if(1 == $rowResults['option_weightage']){ echo 'selected = "selected"'; }; ?>>&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;</option>
              <option value="2"<?php if(2 == $rowResults['option_weightage']){ echo 'selected = "selected"'; }; ?>>&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;</option>
              <option value="3"<?php if(3 == $rowResults['option_weightage']){ echo 'selected = "selected"'; }; ?>>&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;</option>
              <option value="4"<?php if(4 == $rowResults['option_weightage']){ echo 'selected = "selected"'; }; ?>>&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;</option>
              <option value="5"<?php if(5 == $rowResults['option_weightage']){ echo 'selected = "selected"'; }; ?>>&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;</option>
          </select></td>
          <!-- Modified by Hien on 13 Oct for formatting the table-->
            <?php if ($j > 0){ ?>
            </tr>
            <?php }
            
            // next result
			$rowResults = mysql_fetch_assoc($resultResults); 
		} ?>
	  </tr>
    </table>
    <?php
        
        // next option
		$rowOptions = mysql_fetch_assoc($resultOptions);
	}
	?>
    </div>
</div>
  <table border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <th valign="top" scope="row"><input type="button" name="addOptionBtn<?php echo $question; ?>" id="addOptionBtn<?php echo $question; ?>" value="Add new option" onClick="QuizQuestionMulti.addOption(<?php echo $question; ?>)" /></th>
    </tr>
    <tr>
      <td valign="top" class="desc" scope="row">Create a new option for this question</td>
    </tr>
  </table>
</div>
<?php 		$question++;
		}while($row_getQuery = mysql_fetch_assoc($getQuery));
	}
	}
	
}elseif(isset($_GET['delete'])){
	// delete the question
	require('member.php');
	require('quiz.php');
	
	// also pass in the member id for security check
	$quiz = new Quiz($_GET['id']);
	$member = new Member();
	if(!$quiz->removeQuestionMulti($_GET['question'], $member->id)){
		echo "Delete not authorized";
	}
}else{
// get result number
$question = $_GET['questionNumber'];
$quiz = $_GET['id'];
?>

		<?php if($mode == "simple"){ //FOR NEW SIMPLE MODE?>
<div id="q<?php echo $question; ?>" class="questionWidget">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th width="25" scope="row"><a href="javascript:;" onclick="QuizQuestionMulti.remove(<?php echo $question; ?>);"><img src="img/delete.png" alt="" width="16" height="16" border="0" align="absmiddle" title="Remove" /></a></th>
    <th width="80" scope="row"><label for="question_<?php echo $question; ?>">Question</label></th>
    <td><span id="sprytextfield-q<?php echo $question; ?>">
      <input type="text" name="question_<?php echo $question; ?>" id="question_<?php echo $question; ?>" class="questionField" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
</table>
<div id="optionContainer_<?php echo $question; ?>">
  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr class="optionTable">
      <th width="25">&nbsp;</th>
      <th width="80">&nbsp;</th>
      <th align="left">Option Value</th>
      <th width="150" align="center">Contributes to</th>
      <!--<th width="100" align="center">Weightage</th>-->
    </tr>
    <tr>
      <th width="25" scope="row">&nbsp;</th>
      <th width="80" scope="row"><label for="q<?php echo $question; ?>o0" class="optionWidget-<?php echo $question; ?>">Option</label></th>
      <td><span id="sprytextfield-q<?php echo $question; ?>o0" class="sprytextfield">
        <input name="q<?php echo $question; ?>o0" type="text" class="optionField" id="q<?php echo $question; ?>o0" />
        <span class="textfieldRequiredMsg">Enter a value for this option!</span></span></td>
      <td width="150"><select name="q<?php echo $question; ?>r0" class="optionSelect" id="q<?php echo $question; ?>r0">
          <?php foreach($results as $item){ ?>
          <option value="<?php echo $item[0]; ?>"><?php echo $item[1]; ?></option>
          <?php } ?>
      </select></td>
      <!--<td width="100"><select name="q<?php echo $question; ?>w0" id="q<?php echo $question; ?>w0">
          <option value="1">A little</option>
          <option value="2">Somewhat</option>
          <option value="3">A lot</option>
      </select></td>-->
    </tr>
    <tr>
    <th width="25" scope="row">&nbsp;</th>
      <th width="80" scope="row"><label for="q<?php echo $question; ?>o1" class="optionWidget-<?php echo $question; ?>">Option</label></th>
      <td><span id="sprytextfield-q<?php echo $question; ?>o1" class="sprytextfield">
        <input name="q<?php echo $question; ?>o1" type="text" class="optionField" id="q<?php echo $question; ?>o1" />
        <span class="textfieldRequiredMsg">Enter a value for this option!</span></span></td>
      <td width="150"><select name="q<?php echo $question; ?>r1" class="optionSelect" id="q<?php echo $question; ?>r1">
          <?php foreach($results as $item){ ?>
          <option value="<?php echo $item[0]; ?>"><?php echo $item[1]; ?></option>
          <?php } ?>
      </select></td>
      <!--<td width="100"><select name="q<?php //echo $question; ?>w1" id="q<?php //echo $question; ?>w1">
          <option value="1">A little</option>
          <option value="2">Somewhat</option>
          <option value="3">A lot</option>
      </select></td>-->
    </tr>
  </table>
</div>
  <table border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <th valign="top" scope="row"><input type="button" name="addOptionBtn<?php echo $question; ?>" id="addOptionBtn<?php echo $question; ?>" value="Add new option" onClick="QuizQuestionMulti.addOption(<?php echo $question; ?>)" /></th>
    </tr>
    <tr>
      <td valign="top" class="desc" scope="row">Create a new option for this question</td>
    </tr>
  </table>
</div>		
		<?php } ?>
		<?php if($mode == "accurate"){ //FOR NEW ACCURATE MODE?> 
<div id="q<?php echo $question; ?>" class="questionWidget">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th width="25" scope="row"><a href="javascript:;" onclick="QuizQuestionMulti.remove(<?php echo $question; ?>);"><img src="img/delete.png" alt="" width="16" height="16" border="0" align="absmiddle" title="Remove" /></a></th>
    <th width="80" scope="row"><label for="question_<?php echo $question; ?>">Question</label></th>
    <td><span id="sprytextfield-q<?php echo $question; ?>">
      <input type="text" name="question_<?php echo $question; ?>" id="question_<?php echo $question; ?>" class="questionField" />
      <span class="textfieldRequiredMsg">A value is required.</span></span></td>
  </tr>
</table>
<div id="optionContainer_<?php echo $question; ?>">
  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr class="optionTable">
      <th width="25">&nbsp;</th>
      <th width="80">&nbsp;</th>
      <th align="left">Option Value</th>
      <th width="150" align="center">Contributes to</th>
      <th width="100" align="left">Weightage</th>
    </tr>
    <tr>
    <th width="25" scope="row">&nbsp;</th>
      <th width="80" scope="row"><label for="q<?php echo $question; ?>o0" class="optionWidget-<?php echo $question; ?>">Option</label></th>
      <td><span id="sprytextfield-q<?php echo $question; ?>o0" class="sprytextfield">
        <input name="q<?php echo $question; ?>o0" type="text" class="optionField" id="q<?php echo $question; ?>o0"/>
        <span class="textfieldRequiredMsg">Enter a value for this option!</span></span></td>

		<?php $result_count = 0; //ADD BY LIEN, TO BE USED BELOW ?>		
		
	<?php foreach($results as $item){ //CHANGE CSS ?>
        <!-- Modified by Hien on 13 Oct for formatting the table-->
		<?php if ($result_count > 0){ ?>
        <tr class = "optionTable">
                  <th width="25">&nbsp;</th>
                  <th width="80">&nbsp;</th>
                  <th align="left">&nbsp;</th>
        <?php } ?>
		<input type="hidden" name="q<?php echo $question; ?>o0r<?php echo $result_count; ?>" id="q<?php echo $question; ?>o0r<?php echo $result_count; ?>" value="<?php echo $item[0]; ?>" />
		<td width="150" align="center"><?php echo $item[1]; ?></td>
      <td width="100"><select name="q<?php echo $question; ?>o0w<?php echo $result_count; ?>" id="q<?php echo $question; ?>o0w<?php echo $result_count; ?>">
          <option value="0">&nbsp;&nbsp;&nbsp;&nbsp;0&nbsp;&nbsp;&nbsp;</option>
		  <option value="1">&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;</option>
          <option value="2">&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;</option>
          <option value="3">&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;</option>
		  <option value="4">&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;</option>
		  <option value="5">&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;</option>
      </select></td>
       <!-- Modified by Hien on 13 Oct for formatting the table-->
		<?php if ($result_count > 0){ ?>
        </tr>
        <?php } ?>
      <tr></tr>

                  <?php $result_count++; ?>
    <?php } //end  foreach results as item?>
	  
	  </tr>
    <tr>
      <th width="25" scope="row">&nbsp;</th>
      <th width="80" scope="row"><label for="q<?php echo $question; ?>o1" class="optionWidget-<?php echo $question; ?>">Option</label></th>
      <td><span id="sprytextfield-q<?php echo $question; ?>o1" class="sprytextfield">
        <input name="q<?php echo $question; ?>o1" type="text" class="optionField" id="q<?php echo $question; ?>o1" />
        <span class="textfieldRequiredMsg">Enter a value for this option!</span></span></td>


		<?php $result_count = 0; //ADD BY LIEN, TO BE USED BELOW ?>		
		
	<?php foreach($results as $item){ //CHANGE CSS ?>
 <!-- Modified by Hien on 13 Oct for formatting the table-->
		<?php if ($result_count > 0){ ?>
        <tr class = "optionTable">
                  <th width="25">&nbsp;</th>
                  <th width="80">&nbsp;</th>
                  <th align="left">&nbsp;</th>
        <?php } ?>
		<input type="hidden" name="q<?php echo $question; ?>o1r<?php echo $result_count; ?>" id="q<?php echo $question; ?>o1r<?php echo $result_count; ?>" value="<?php echo $item[0]; ?>" />
		<td width="150" align="center"><?php echo $item[1]; ?></td>
      <td width="100"><select name="q<?php echo $question; ?>o1w<?php echo $result_count; ?>" id="q<?php echo $question; ?>o1w<?php echo $result_count; ?>">
          <option value="0">&nbsp;&nbsp;&nbsp;&nbsp;0&nbsp;&nbsp;&nbsp;</option>
		  <option value="1">&nbsp;&nbsp;&nbsp;&nbsp;1&nbsp;&nbsp;&nbsp;</option>
          <option value="2">&nbsp;&nbsp;&nbsp;&nbsp;2&nbsp;&nbsp;&nbsp;</option>
          <option value="3">&nbsp;&nbsp;&nbsp;&nbsp;3&nbsp;&nbsp;&nbsp;</option>
		  <option value="4">&nbsp;&nbsp;&nbsp;&nbsp;4&nbsp;&nbsp;&nbsp;</option>
		  <option value="5">&nbsp;&nbsp;&nbsp;&nbsp;5&nbsp;&nbsp;&nbsp;</option>
      </select></td>
      <!-- Modified by Hien on 13 Oct for formatting the table-->
		<?php if ($result_count > 0){ ?>
        </tr>
        <?php } ?>
      <tr></tr>

                  <?php $result_count++; ?>
    <?php } //end  foreach results as item?>

	  </tr>
  </table>
</div>
  <table border="0" align="center" cellpadding="5" cellspacing="0">
    <tr>
      <th valign="top" scope="row"><input type="button" name="addOptionBtn<?php echo $question; ?>" id="addOptionBtn<?php echo $question; ?>" value="Add new option" onClick="QuizQuestionMulti.addOption(<?php echo $question; ?>)" /></th>
    </tr>
    <tr>
      <td valign="top" class="desc" scope="row">Create a new option for this question</td>
    </tr>
  </table>
</div>
		<?php } ?>		

<?php } ?>
