<!-- creates one new option object for particular question of a particular quiz -->

<?php 
require("quizrooDB.php");

//***********************************************ADD BY LIEN************************************************//
$queryMode = sprintf("SELECT display_mode FROM q_quizzes WHERE quiz_id = %d", GetSQLValueString($_GET['id'], "int"));
$resultMode =  mysql_query($queryMode, $quizroo) or die(mysql_error());
$row_resultMode = mysql_fetch_assoc($resultMode);
$resultforMode = array();
$mode = "";
do{
	$resultforMode[] = array($row_resultMode['display_mode']);
	//echo $row_resultMode['mode']; //debugging purpose
	if ($row_resultMode['display_mode'] == "multi_simple")
		$mode = "simple";
	if ($row_resultMode['display_mode'] == "multi_accurate")
		$mode = "accurate";
}while($row_resultMode = mysql_fetch_assoc($resultMode));

//***********************************************END OF ADD BY LIEN************************************************//

if(isset($_GET['delete'])){
	// delete the option
	require('member.php');
	require('quiz.php');
	
	// also pass in the member id for security check
	$quiz = new Quiz($_GET['id']);
	$member = new Member();
	if(!$quiz->removeOptionMulti($_GET['option'], $member->id)){
		echo "Delete not authorized";
	}
}else{
// get result number
$question = $_GET['questionNumber'];
$option = $_GET['optionNumber'];
$quiz = $_GET['id'];

// prepare result options
$querySQL = "SELECT result_id, result_title FROM q_results_multi WHERE fk_quiz_id = ".GetSQLValueString($quiz, "int");
$resultID = mysql_query($querySQL, $quizroo) or die(mysql_error());
$row_resultID = mysql_fetch_assoc($resultID);

$results = array();

do{
	$results[] = array($row_resultID['result_id'], $row_resultID['result_title']);
}while($row_resultID = mysql_fetch_assoc($resultID));

mysql_free_result($resultID);
?>
<?php if ($mode == "accurate") { ?>
<!-- Shows that 1 option -->
<div id="cq<?php echo $question; ?>o<?php echo $option; ?>">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th width="25" scope="row"><a href="javascript:;" onclick="QuizQuestionMulti.removeOption(<?php echo $question; ?>, <?php echo $option; ?>);"><img src="img/delete.png" width="16" height="16" border="0" align="absmiddle" title="Remove" /></a></th>
    <th width="80" scope="row"><label for="q<?php echo $question; ?>o<?php echo $option; ?>">Option</label></th>
    <td><span id="sprytextfield-q<?php echo $question; ?>o<?php echo $option; ?>" class="sprytextfield">
      <input name="q<?php echo $question; ?>o<?php echo $option; ?>" type="text" class="optionField" id="q<?php echo $question; ?>o<?php echo $option; ?>" />
    <span class="textfieldRequiredMsg">Enter a value for this option!</span></span></td>

 <!--td width="150"-->
 	<?php $result_count = 0; ?>
     <?php foreach($results as $item){ ?>
     <!-- Modified by Hien on 12 Oct for formatting the table-->
         <?php if ($result_count > 0) { ?>
         <tr class = "optionTable">
         <th width="25">&nbsp;</th>
         <th width="80">&nbsp;</th>
         <th align="left">&nbsp;</th>
         <?php } ?>
         <td width="150" align="center"> <?php echo $item[1]; ?> </td>  
         
          <input type="hidden" name="q<?php echo $question; ?>o<?php echo $option;?>r<?php echo $result_count; ?>" id="q<?php echo $question; ?>o<?php echo $option;?>r<?php echo $result_count; ?>" value="<?php echo $item[0] ?>" /> 
                        
         <td width="100" align = "center"><select name="q<?php echo $question;?>o<?php echo $option;?>w<?php echo $result_count?>" id="q<?php echo $question; ?>o<?php echo $option;?>w<?php echo $result_count?>">
        
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
  </tr>
</table>
</div>
<?php } //end if mode = accurate ?>
<?php if ($mode == "simple") { ?>
<!-- Shows that 1 option -->
<div id="cq<?php echo $question; ?>o<?php echo $option; ?>">
<table width="100%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th width="25" scope="row"><a href="javascript:;" onclick="QuizQuestionMulti.removeOption(<?php echo $question; ?>, <?php echo $option; ?>);"><img src="img/delete.png" width="16" height="16" border="0" align="absmiddle" title="Remove" /></a></th>
    <th width="80" scope="row"><label for="q<?php echo $question; ?>o<?php echo $option; ?>">Option</label></th>
    <td><span id="sprytextfield-q<?php echo $question; ?>o<?php echo $option; ?>" class="sprytextfield">
      <input name="q<?php echo $question; ?>o<?php echo $option; ?>" type="text" class="optionField" id="q<?php echo $question; ?>o<?php echo $option; ?>" />
    <span class="textfieldRequiredMsg">Enter a value for this option!</span></span></td>
    <td width="150"><select name="q<?php echo $question; ?>r<?php echo $option; ?>" class="optionSelect" id="q<?php echo $question; ?>r<?php echo $option; ?>">
	  <?php foreach($results as $item){ ?>
		<option value="<?php echo $item[0]; ?>"><?php echo $item[1]; ?></option>
      <?php } ?>
    </select></td>
  </tr>
</table>
</div>
<?php } //end if mode = simple?>
<?php } ?>