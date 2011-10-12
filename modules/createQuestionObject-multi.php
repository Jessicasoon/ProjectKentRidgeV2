<!-- Creates 1 new question object for a particular quiz. Comes with 2 default options. If additional option required: will call createOptionObject-multi.php
	  or load question for particular quiz if user was to modify quiz that was already created.
      -->

<?php require("quizrooDB.php");

			
// prepare result options
		
$querySQL = sprintf("SELECT `result_id`, `result_title` FROM q_results_multi WHERE fk_quiz_id = %d ORDER BY result_id" , GetSQLValueString($_GET['id'], "int"));
$resultID = mysql_query($querySQL, $quizroo) or die(mysql_error());
$row_resultID = mysql_fetch_assoc($resultID);

$results = array();
$total_results = 0;

do{
	$results[] = array($row_resultID['result_id'], $row_resultID['result_title']);
	$total_results++;
}while($row_resultID = mysql_fetch_assoc($resultID));

mysql_free_result($resultID);

$result_count = 0; //ADD BY LIEN, TO BE USED BELOW
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
if(isset($_GET['load'])){ //user is modifying quiz, load from database
	require('quizrooDB.php');
	
	$query = sprintf("SELECT question_id, question, question_image, question_order FROM q_questions WHERE fk_quiz_id = %d", GetSQLValueString($_GET['id'], "int"));
	$getQuery = mysql_query($query, $quizroo) or die(mysql_error());
	$row_getQuery = mysql_fetch_assoc($getQuery);
	$totalRows_getQuery = mysql_num_rows($getQuery);
	
	$question = 0;
	
	//loops through questions of particular quiz
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
      <?php if ($mode == "accurate") {?>
      	<th width="100" align="center">Weightage</th> <?php } ?>
    </tr>
  </table>
    <?php if ($mode == "simple") {
		// get options for particular question
		$queryOption = sprintf("SELECT `option_id`, `option`, `fk_result_id`, `option_weightage` FROM q_options_multi WHERE fk_question_id = %d ORDER BY option_id", GetSQLValueString($row_getQuery['question_id'], "int"));
		$getOption = mysql_query($queryOption, $quizroo) or die(mysql_error());
		$row_getOption = mysql_fetch_assoc($getOption);
		$totalRows_getOption = mysql_num_rows($getOption);
		
		$option = 0;
		
		//loops to display options
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
			  
		  <?php $option++; }while($row_getOption = mysql_fetch_assoc($getOption)); } ?>
		  
		      </tr>
    </table>
    </div>
</div>

	 <?php } // end if $mode == simple ?>
	  
      <?php if ($mode == "accurate") { 
           
            $queryOption = sprintf("SELECT `option`,`option_id`, `fk_result_id`, `option_weightage` FROM q_options_multi WHERE fk_question_id = %d", GetSQLValueString($row_getQuery['question_id'], "int"));
			$getOption = mysql_query($queryOption, $quizroo) or die(mysql_error());
			$row_getOption = mysql_fetch_assoc($getOption);
			$totalRows_getOption = mysql_num_rows($getOption);
			
			$option = 0; 
			$option_countForResult = 0;
			$result_count = 0;
			if($totalRows_getOption > 0){
			do{ ?>
            
            <?php if ($result_count == 0) { ?>
            <div id="cq<?php echo $question; ?>o<?php echo $option; ?>">
		<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
		<tr>
		  <th width="25" scope="row"><input type="hidden" name="uq<?php echo $question; ?>o<?php echo $option; ?>" id="uq<?php echo $question; ?>o<?php echo $option; ?>" value="<?php echo $row_getOption['option_id']; ?>" /><a href="javascript:;" onclick="QuizQuestionMulti.removeOption(<?php echo $question; ?>, <?php echo $option; ?>);"><img src="img/delete.png" width="16" height="16" border="0" align="absmiddle" title="Remove" /></a></th>
		  <th width="80" scope="row"><label for="q<?php echo $question; ?>o<?php echo $option; ?>" class="optionWidget-<?php echo $question; ?>">Option</label></th>
		  <td><span id="sprytextfield-q<?php echo $question; ?>o<?php echo $option; ?>" class="sprytextfield">
			<input name="q<?php echo $question; ?>o<?php echo $option; ?>" type="text" class="optionField" id="q<?php echo $question; ?>o<?php echo $option; ?>" value="<?php echo $row_getOption['option']; ?>" />
			<span class="textfieldRequiredMsg">Enter a value for this option!</span></span></td>
			  <!--<td width="150"> </td>
 	  </tr>
   	 </table>
   	  </div>-->
      <?php } //end if $result_count == 0 ?>
        
            <!-- Modified by Hien on 12 Oct for formatting the table-->
          		  <?php if ($result_count > 0) { ?>
                  <tr class = "optionTable">
                  <th width="25">&nbsp;</th>
                  <th width="80">&nbsp;</th>
                  <th align="left">&nbsp;</th>
                  <?php } ?>
                  <td width="150" align="center"> <?php echo $results[$result_count][1]; ?> </td>  
                  <input type="hidden" name="q<?php echo $question; ?>o<?php echo $option_countForResult; ?>r<?php echo $result_count; ?>" id="q<?php echo $question; ?>o<?php echo $option_countForResult; ?>r<?php echo $option_countForResult; ?>" value="<?php echo $results[$result_count][0]; ?>" />
         
                  <td width="100"  align = "center"><select name="q<?php echo $question; ?>o<?php echo $option_countForResult; ?>w<?php echo $result_count;?>" id="q<?php echo $question; ?>o<?php echo $option_countForResult; ?>w<?php echo $result_count;?>">
    
                      <option value="1" <?php if(1 == $row_getOption['option_weightage']){ echo ' selected = "selected"'; }; ?>>1</option> 
                      <option value="2" <?php if(2 == $row_getOption['option_weightage']){ echo ' selected = "selected"'; }; ?>>2</option>
                      <option value="3" <?php if(3 == $row_getOption['option_weightage']){ echo ' selected = "selected"'; }; ?>>3</option>  
                  </select></td>
                  
                  <td>
                  <label>q<?php echo $question; ?>o<?php echo $option_countForResult; ?>w<?php echo $result_count;?></label>
                  </td>
                  <td>
                  <label>q<?php echo $question; ?>o<?php echo $option_countForResult; ?>r<?php echo $result_count; ?></label>
                  </td>
                  <td>
         <?php echo $results[$result_count][0]; echo "HELLO"; echo $row_getOption['fk_result_id']; // DEBUG PURPOSE - LIEN?>
                  </td>
                  
                   <!-- Modified by Hien on 12 Oct for formatting the table-->
          		  <?php if ($result_count > 0) { ?>        
                 </tr> <!-- end tr class = "optionTable"-->
                  <?php } ?>
                 
			  <?php $result_count++; ?>  
			  <?php if ( $result_count == $total_results ) { $result_count = 0; $option_countForResult++; }?>

             
			<?php $option++; }while($row_getOption = mysql_fetch_assoc($getOption)); } ?>
      
	 <?php } //end elseif mode = accurate?>
    </tr>
    </table>
    </div>
    <?php ?>
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
}elseif(isset($_GET['delete'])){ // from Quiz.create.js's remove: function(id) [open braces]  url: "../modules/createQuestionObject-multi.php?delete",
	// delete the question
	require('member.php');
	require('quiz.php');
	
	// also pass in the member id for security check
	$quiz = new Quiz($_GET['id']);
	$member = new Member();
	if(!$quiz->removeQuestionMulti($_GET['question'], $member->id)){
		echo "Delete not authorized";
	}
} else{
//////////////////////////////NEW QUESTION: CREATE QUESTION OBJECT ////////////////////
// get result number
$question = $_GET['questionNumber'];
$quiz = $_GET['id'];
?>
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
<!-- display default of 2 options per question -->
<div id="optionContainer_<?php echo $question; ?>">
  <table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
    <tr class="optionTable">
      <th width="25">&nbsp;</th>
      <th width="80">&nbsp;</th>
      <th align="left">Option Value</th>
      <th width="150" align="center">Contributes to</th>
      <?php if ($mode == "accurate") {?>
      	<th width="100" align="center">Weightage</th> <?php } ?>
    </tr>
    <tr>
      <th width="25" scope="row">&nbsp;</th>
      <th width="80" scope="row"><label for="q<?php echo $question; ?>o0" class="optionWidget-<?php echo $question; ?>">Option</label></th>
      <td><span id="sprytextfield-q<?php echo $question; ?>o0" class="sprytextfield">
        <input name="q<?php echo $question; ?>o0" type="text" class="optionField" id="q<?php echo $question; ?>o0" />
        <span class="textfieldRequiredMsg">Enter a value for this option!</span></span></td>
      
      
      <?php if ($mode == "simple") { ?>
      <td width="150"><select name="q<?php echo $question; ?>r1" class="optionSelect" id="q<?php echo $question; ?>r1">
          <?php foreach($results as $item){ ?>
          <option value="<?php echo $item[0]; ?>"> <?php echo $item[1]; ?></option>
          <?php } ?>
      </select></td>
      <?php } ?>
       
      <?php if ($mode == "accurate") { 
	  	$result_count = 0; ?>
      <!--td width="150"-->
          <?php foreach($results as $item){ ?>
           <!-- Modified by Hien on 12 Oct for formatting the table-->
          		  <?php if ($result_count > 0) { ?>
                  <tr class = "optionTable">
                  <th width="25">&nbsp;</th>
                  <th width="80">&nbsp;</th>
                  <th align="left">&nbsp;</th>
                  <?php } ?>
                  <td width="150" align="center"> <?php echo $item[1]; ?> </td>  
                  
                  <input type="hidden" name="q<?php echo $question; ?>o0r<?php echo $result_count; ?>" id="q<?php echo $question; ?>o0r<?php echo $result_count; ?>" value="<?php echo $item[0] ?>" />        
                  
                  <td width="100" align = "center"><select name="q<?php echo $question;?>o0w<?php echo $result_count; ?>" id="q<?php echo $question; ?>o0w<?php echo $result_count; ?>">
                
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                  </select></td> 
                  <!-- Modified by Hien on 12 Oct for formatting the table-->
          		  <?php if ($result_count > 0) { ?>
                  </tr>
                  <?php } ?>
                  <?php $result_count++; ?>
             <?php } //end  foreach results as item?>
          <?php } //end if $mode == accurate?>
          <!--/td-->

    </tr>
    <tr>
      <th width="25" scope="row">&nbsp;</th>
      <th width="80" scope="row"><label for="q<?php echo $question; ?>o1" class="optionWidget-<?php echo $question; ?>">Option</label></th>
      <td><span id="sprytextfield-q<?php echo $question; ?>o1" class="sprytextfield">
        <input name="q<?php echo $question; ?>o1" type="text" class="optionField" id="q<?php echo $question; ?>o1" />
        <span class="textfieldRequiredMsg">Enter a value for this option!</span></span></td>
     
     <?php ///////////////////////////////REPLACES THE BOTTOM ONE: LIEN ////////////////////////////?>    
    
      <?php if ($mode == "simple") { ?>
      <td width="150"><select name="q<?php echo $question; ?>r1" class="optionSelect" id="q<?php echo $question; ?>r1">
          <?php foreach($results as $item){ ?>
          <option value="<?php echo $item[0]; ?>"> <?php echo $item[1]; ?></option>
          <?php } ?>
      </select></td>
      <?php } ?>
      <?php if ($mode == "accurate") { 
	  	$result_count = 0; ?>
      <!--td width="150"-->
          <?php foreach($results as $item){ ?>
          <!-- Modified by Hien on 12 Oct for formatting the table-->
          		  <?php if ($result_count > 0) { ?>
                  <tr class = "optionTable">
                  <th width="25">&nbsp;</th>
                  <th width="80">&nbsp;</th>
                  <th align="left">&nbsp;</th>
                  <?php } ?>
                  <td width="150" align="center"> <?php echo $item[1]; ?> </td>  
                  
                  <input type="hidden" name="q<?php echo $question; ?>o1r<?php echo $result_count; ?>" id="q<?php echo $question; ?>o1r<?php echo $result_count; ?>" value="<?php echo $item[0] ?>" /> 
                    
                  <td width="100" align ="center"><select name="q<?php echo $question;?>o1w<?php echo $result_count; ?>" id="q<?php echo $question; ?>o1w<?php echo $result_count; ?>">
                      <option value="1">1</option>
                      <option value="2">2</option>
                      <option value="3">3</option>
                  </select></td>
                  <!-- Modified by Hien on 12 Oct for formatting the table-->
                  <?php if ($result_count > 0) { ?> 
                  </tr>
                   <?php } ?>
                  <?php $result_count++; ?>
             <?php } //end foreach results as item?>
          <?php } //end if mode == accurate?>
          </td> <?php /////////////////////////////// END OF REPLACEMENT : LIEN ////////////////////////////?>    
     <?php ///////////////////////////////REPLACED BY THE TOP : START DELETE: LIEN ////////////////////////////?>     
     <!-- <td width="150"><select name="q<?php //echo $question; ?>r1" class="optionSelect" id="q<?php //echo $question; ?>r1">
          <?php //foreach($results as $item){ ?>
          <option value="<?php //echo $item[0]; ?>"> <?php //echo $item[1]; ?></option>
          <?php// } ?>
      </select></td>
      <?php //if ($mode == "accurate")  ?>
          <td width="100"><select name="q<?php // echo $question; ?>w0" id="q<?php //echo $question; ?>w0">
              <option value="1">A little</option>
              <option value="2">Somewhat</option>
              <option value="3">A lot</option>
          </select></td> 
	 <?php // end if mode == accurate ?> -->
     <?php /////////////////////////////// END OF DELETE : LIEN ////////////////////////////?>    
     
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
