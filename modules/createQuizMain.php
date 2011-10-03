<script src="jquery.js"></script>
<script type="text/javascript">
	 $(document).ready(function(){
     $("#personality").change(function(event){
		 event.preventDefault();
		 $("#blk-1").hide("fast");
		 $("#blk-2").show("fast");
   	 });
	 $("#test").change(function(event){
		 event.preventDefault();
		 $("#blk-2").hide("fast");
		 $("#blk-1").show("fast");
   	 });
	 $("#step0").click(function(event){
		 event.preventDefault();
		 var answer = confirm("Are you sure you want to create a quiz of these settings? Once you have passed this step, you cannot modify the quiz type");
		 if (answer){
			 $("#step_0").hide("");
			 $("#step_1").show("");
		 }
		 else return false;
   	 });
	 $("#step0_true").click(function(event){
		 event.preventDefault();
			 $("#step_0").hide("");
			 $("#step_1").show("");
   	 });
	 $("#step1").click(function(event){
		 event.preventDefault();
		 $("#step_1").hide("");
		 $("#step_0").show("");
   	 });
 });
</script>
<?php
require('../modules/quizrooDB.php');
require('../modules/uploadFunctions.php');
require("../modules/quiz.php");

if(isset($_GET['step'])){
// now check whether this quiz actually belongs to this user
if(isset($_GET['id'])){
$quiz = new Quiz($_GET['id']);
//$mode = $quiz->getMode();
if($quiz->exists() && $quiz->isOwner($member->id)){
$quiz_state = true;
// unpublish the quiz
$quiz->unpublish($member->id);
$unikey = $quiz->quiz_key;
}else{
$quiz_state = false;
unset($quiz);
}
}else{
$quiz_state = false;
unset($quiz);
}

if($quiz_state){

// THE FIRST STEP (Returning): Quiz Type
switch($_GET['step']){

case 1:
// populate the categories
$query_listCat = "SELECT cat_id, cat_name FROM q_quiz_cat";
$listCat = mysql_query($query_listCat, $quizroo) or die(mysql_error());
$row_listCat = mysql_fetch_assoc($listCat);
$totalRows_listCat = mysql_num_rows($listCat);
//Modified on 27 Sep by Hien for jquery
$queryType = sprintf("SELECT fk_quiz_type FROM q_quizzes WHERE quiz_id = %d", $quiz->quiz_id);
$resultType =  mysql_query($queryType, $quizroo) or die(mysql_error());
$row_resultType = mysql_fetch_assoc($resultType);
?>
<form action="../modules/createQuizEngine.php?step=1" method="post"
	enctype="multipart/form-data" name="createQuiz" id="createQuiz"
	onsubmit="return submitCheck(Spry.Widget.Form.validate(this));"><input
	type="hidden" name="id" value="<?php echo $quiz->quiz_id; ?>" /> <input
	type="hidden" name="unikey" value="<?php echo $unikey; ?>" />
<div id = "step_0" style="display:none">
<div id="progress-container" class="framePanel rounded">
<h2>Create Quiz: Choose Quiz Type</h2>
<div class="content-container">
<p>You're just <strong>5</strong> steps away from creating your own
quiz! <em>Step 1</em> is for you to choose your favourite quiz type and
how you want it to be displayed.</p>
<p>Please complete this step then move on to creating quiz process</p>
<ul class="rounded">
	<li class="current start"><strong>Step 1</strong> Quiz Type</li>
	<li><strong>Step 2</strong> Quiz Information</li>
	<li><strong>Step 3</strong> Results</li>
	<li><strong>Step 4</strong> Question</li>
	<li><strong>Step 5</strong> Publish</li>
</ul>
<p style="color:#FF0000">You can modify display mode but you cannot modify quiz type because the quiz of this type has been created. If you still wish to, just delete this quiz and create an entirely new quiz.</p>
</div>
</div>

<div id="form_step0" class="framePanel rounded">
<table width="100%" style="font-size: 12px; font: Verdana, Arial, Helvetica, sans-serif">
	<!-- Test type-->
	<tr>
      <?php if ($row_resultType['fk_quiz_type'] == "1"){ ?>	
		<td colspan="3"><h2><input id="test" name="type" type="radio" value="1" checked="checked" disabled="disabled"/>Test</h2></td>
	  <?php }else{ ?>
		<td colspan="3"><h2><input id="test" name="type" type="radio" value="1" disabled="disabled"/>Test</h2></td>
	  <?php }?>
	</tr>
	<tr>
		<td valign="top" width="40%">
        <?php if ($row_resultType['fk_quiz_type'] == "1"){ ?>
                <div id = "blk-1">
        <?php } else { ?>
        		<div id = "blk-1" style="display:none">
        <?php } ?>       
				<table>
					<tr>
						<td>Do you want to customize your quiz result?<a href="customnizeTestExpl.php"> What's this?</a></td>
						<!-- To replace the hyperlink-->
					</tr>
					<tr>
						<td><input type="radio" value="test_custom" name="mode1" checked="checked"/>Yes</td>
					</tr>
					<tr>
						<td><input type="radio" value="test_simple" name="mode1"/>No, keep it simple.</td>
					</tr>
				</table>
                </div>		
          </td>
		<td width="60%">
		<div class="content-container">
		<p><strong>Quiz of Test type</strong>: designed to determine knowledge
		of a particular subject based on factual information of it. Hence,
		there are right and wrong answers for each question. Below is an
		example of Test type quiz.</p>
		<table>
			<tr>
				<td>
				<table>
					<tr bgcolor="#FC0">
						<td>Quiz title:</td>
						<td>How well do you know Michael Jackson?</td>
					</tr>
					<tr>
						<td>Question 1:</td>
						<td>Where is he from?</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><input type="radio" disabled="disabled" />
Thailand</td>
				    </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><input type="radio" disabled="disabled" />
The US (Correct
					  answer)</td>
				    </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><input type="radio" disabled="disabled" />
England</td>
				    </tr>
					<tr>
						<td>&nbsp;</td>
						<td>
						<input type="radio" disabled="disabled" />
						Singapore					  </td>
					</tr>
				</table>				</td>
			</tr>
		</table>
		</div>		
        </td>
	</tr>
	<!-- Personality type-->
	<tr>
	<?php if ($row_resultType['fk_quiz_type'] == "2"){ ?>
	  <td colspan="3"><h2><input id="personality" name="type" type="radio" checked="checked" value="2" disabled="disabled"/>Personality</h2></td>
	<?php }else{ ?>
	  <td colspan="3"><h2><input id="personality" name="type" type="radio" value="2" disabled="disabled"/>Personality</h2></td>
	<?php } ?>
	  </tr>
	<tr>
		<td valign="top" width="40%">
        <?php if ($row_resultType['fk_quiz_type'] == "2"){ ?>
                <div id = "blk-2">
        <?php } else { ?>
        		<div id = "blk-2" style="display:none">
        <?php } ?>
				<table>
					<tr>
						<td>Do you want to make your quiz more accurate? <a
							href="customnizeMultiExpl.php">What's this?</a></td>
						<!-- To replace the hyperlink-->
					</tr>
					<tr>
						<td><input type="radio" value="multi_accurate" name="mode2" checked="checked" />Yes</td>
					</tr>
					<tr>
						<td><input type="radio" value="multi_simple" name="mode2" />No, keep it
						simple</td>
					</tr>
				</table>
                </div>				
        </td>
		<td width="60%">
		<div class="content-container">
		<p><strong>Quiz of Personality type:</strong> consisting of questions
		whose purpose is to test on different aspects of a person's characters
		such as behaviors, thoughts and feelings. There is no right or wong
		answer and the result derives from how quiz takers choose their
		reactions in certain situation. Below is an example of Personality
		type quiz.</p>
		<table>
			<tr>
				<td>
				<table>
					<tr bgcolor="#FC0">
						<td width="58">Quiz title:</td>
						<td>How serious are you?</td>
					</tr>
					<tr>
						<td>Question 1:</td>
						<td>When you're with your friends you&hellip;</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><span class="style1">
					    <input type="radio" disabled="disabled" />
					    Make comments from
					  time to time. You aren't quiet or boisterous</span></td>
				    </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><span class="style1">
					    <input type="radio" disabled="disabled" />
					    You just listen to
					  what everyone else is talking about.</span></td>
				    </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><span class="style1">
					    <input type="radio" disabled="disabled" />
				      Always making jokes.</span></td>
				    </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><span class="style1">
					    <input type="radio" disabled="disabled" />
Don't care about
					  anything.</span></td>
				    </tr>
				</table>				
                </td>
				
			</tr>
		</table>
		</div>		
        </td>
        </tr>
    <tr>
    	<td></td>
        <td align="right"><input type="button" value="Next Step!" id="step0_true"/></td>
	</tr>
</table>
</div>
</div>
<div id="step_1">
<div id="progress-container" class="framePanel rounded">
<h2>Create Quiz: Quiz Information</h2>
<div class="content-container">
<p>You're just <strong>4</strong> steps away from creating your own
quiz! <em>Step 2</em> contains all the basic information we need to help
you setup your quiz. It allows you to tell a potential quiz taker what
insights your quiz intends to deliver.</p>
<p>If you have prepared several images for quiz, you can upload them all
at once! You can choose which images to use at every step of the
creation process.</p>
<ul class="rounded">
	<li class="completed_last start"><strong>Step 1</strong> Quiz Type</li>
	<li class="current"><strong>Step 2</strong> Quiz Information</li>
	<li><strong>Step 3</strong> Results</li>
	<li><strong>Step 4</strong> Question</li>
	<li><strong>Step 5</strong> Publish</li>
</ul>
</div>
</div>
<div id="create-quiz" class="frame rounded">
<table width="95%" border="0" align="center" cellpadding="5"
	cellspacing="0">
	<tr>
		<th width="120" valign="top" scope="row"><label for="quiz_title">Title</label></th>
		<td><span id="sprytextfield0" class="sprytextfield"> <input
			type="text" name="quiz_title" id="quiz_title"
			value="<?php echo $quiz->quiz_name; ?>" /> <span
			class="textfieldRequiredMsg">A title is required.</span></span> <span
			class="desc">Give your Quiz a meaningful title! Your title will be
		the first thing that catches a reader's attention.</span></td>
	</tr>
	<tr>
		<th width="120" valign="top" scope="row"><label for="quiz_description">Description</label></th>
		<td><span id="sprytextarea0" class="sprytextarea"> <textarea
			name="quiz_description" id="quiz_description" cols="45" rows="5"><?php echo $quiz->quiz_description; ?></textarea>
		<span class="textareaRequiredMsg">Description should not be blank!</span></span><span
			class="desc">Provide a short description on what your quiz is about.</span></td>
	</tr>
	<tr>
		<th valign="middle" scope="row"><label for="quiz_cat">Topic</label></th>
		<td><select name="quiz_cat" id="quiz_cat">
		<?php do { ?>
			<option value="<?php echo $row_listCat['cat_id']; ?>"
			<?php if($row_listCat['cat_id'] == $quiz->fk_quiz_cat){ echo "selected"; }; ?>><?php echo $row_listCat['cat_name']?></option>
			<?php } while ($row_listCat = mysql_fetch_assoc($listCat));
			$rows = mysql_num_rows($listCat);
			if($rows > 0) {
			mysql_data_seek($listCat, 0);
			$row_listCat = mysql_fetch_assoc($listCat);
			} ?>
		</select></td>
	</tr>
	<tr>
		<th rowspan="2" valign="top" scope="row"><label>Quiz Picture</label> <input
			type="hidden" name="result_picture_0" id="result_picture_0"
			value="<?php echo $quiz->quiz_picture; ?>" /></th>
		<td class="desc">
		<div id="swfupload-control-0" class="swfupload-control">
		<table border="0" cellspacing="0" cellpadding="0">
			<tr>
				<td><input name="uploader-0" type="button" id="uploader-0"/></td>
				<td valign="middle" class="formDesc">Upload a picture (jpg, gif or
				png); You can select more than 1 file!</td>
			</tr>
		</table>
		<table border="0" cellspacing="0" cellpadding="5">
			<tr>
				<td>
				<div id="selected-image-0" class="selected-image"></div>
				</td>
				<td>
				<p id="queuestatus-0"></p>
				</td>
			</tr>
		</table>
		<ol id="log-0" class="log">
		</ol>
		</div>
		</td>
	</tr>
	<tr>
		<td class="desc">
		<div id="pictureChoser_0"><?php if(sizeof(glob("../quiz_images/".$unikey."*")) > 0){ ?>
		<table border="0" cellspacing="0" cellpadding="3">
			<tr>
				<td><span class="formDesc">OR click on a picture below to use it as
				the quiz picture</span></td>
			</tr>
			<tr>
				<td><?php // return uploaded images if(
			$count = 0;
			if($unikey != ""){ foreach(glob("../quiz_images/".$unikey."*") as $filename){ ?>
				<a href="javascript:;"
					onClick="selectImage(0, '<?php echo str_replace("'", "\\'", basename($filename)); ?>')"><img
					src="../quiz_images/imgcrop.php?w=80&h=60&f=<?php echo basename($filename); ?>"
					width="80" height="60" id="d<?php echo $count; ?>"
					class="selectImage"></a> <?php $count++; }} ?></td>
			</tr>
		</table>
		<?php } ?></div>
		</td>
	</tr>
	<tr>
		<th valign="top" scope="row">&nbsp;</th>
		<td align="right" class="desc"><input type="button" value='Previous Step' id="step1">&nbsp; <input type="submit"
			name="next" id="next" value="Next Step!" /></td>
	</tr>
</table>
</div>
</div>
</form>
<?php // THE SECOND STEP: Quiz Results
		break; case 2:
		?>
<div id="progress-container" class="framePanel rounded">
<h2>Create Quiz: Results</h2>
<div class="content-container">
<p>You're just <strong>3</strong> steps away from creating your own
quiz! <em>Step 3</em> allows you to define the results of your quiz.
Quiz results appear at the end of each quiz. Depending on what options
the quiz taker has chosen, the result which carries the most weightage
from the options will be the final quiz result. You can add as many
results as you like!</p>
<ul class="rounded">
	<li class="complete_full start"><strong>Step 1</strong> Quiz Type</li>
	<li class="completed_last"><strong>Step 2</strong> Quiz
	Information</li>
	<li class="current"><strong>Step 3</strong> Results</li>
	<li><strong>Step 4</strong> Question</li>
	<li><strong>Step 5</strong> Publish</li>
</ul>
</div>
</div>
<div id="create-quiz" class="frame rounded">
<?php
		$queryMode = sprintf("SELECT display_mode FROM q_quizzes WHERE quiz_id = %d", $quiz->quiz_id);
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
		
?>

<?php if($mode == "simple" || $mode == "accurate"){ ?>
<form action="../modules/createQuizEngine.php?step=2" method="post"
	enctype="multipart/form-data" name="createQuiz" id="createQuiz"
	onsubmit="return submitCheckMulti(Spry.Widget.Form.validate(this));"><input
	type="hidden" name="id" value="<?php echo $quiz->quiz_id; ?>" />
<?php }else{ ?>
<form action="../modules/createQuizEngine.php?step=2" method="post"
	enctype="multipart/form-data" name="createQuiz" id="createQuiz"
	onsubmit="return submitCheckTest(Spry.Widget.Form.validate(this));"><input
	type="hidden" name="id" value="<?php echo $quiz->quiz_id; ?>" />
<?php } ?>
	
<div id="createResultContainer">
<p id="resultTip" class="containerTip">Click on the "Add new result"
button to add a result entry!</p>
<?php if($mode == "simple" || $mode == "accurate"){ ?>
<body onload="QuizResultMulti.add()">
<?php }else{ ?>
<body onload="QuizResultTest.add()">
<?php } ?>
</div>

<?php if($mode == "simple" || $mode == "accurate"){ ?>
<div class="add_container">
<input type="submit" name="save" id="prev" value="Previous Step" />&nbsp; 
<input type="button" name="addResultBtn" id="addResultBtn" value="Add new result" onclick="QuizResultMulti.add()" />&nbsp; 
<input type="submit" name="save" id="next" value="Next Step!" />
</div>
<?php }else{ ?>
<div class="add_container">
<input type="submit" name="save" id="prev" value="Previous Step" />&nbsp; 
<input type="button" name="addResultBtn" id="addResultBtn" value="Add new result" onclick="QuizResultTest.add()" />&nbsp;
<input type="submit" name="save" id="next" value="Next Step!" />
</div>
<?php } ?>

<input type="hidden" name="resultCount" id="resultCount" value="0" /></form>
</div>
		<?php // THE THIRD STEP: Quiz Questions
		break; case 3:
		?>
<div id="progress-container" class="framePanel rounded">
<h2>Create Quiz: Questions</h2>
<div class="content-container">
<p>You're just <strong>2</strong> steps away from creating your own
quiz! <em>Step 4</em> allows you to populate your quiz with questions.
You can provide several options for quiz takers to choose for each
question. You should also specify the weightage of each option - how
each option contributes to a result.</p>
<ul class="rounded">
	
	<li class="complete_full start"><strong>Step 1</strong> Quiz Type</li>
	<li class="complete_full "><strong>Step 2</strong> Quiz
	  Information</li>
	<li class="completed_last"><strong>Step 3</strong> Results</li>
	<li class="current"><strong>Step 4</strong> Question</li>
	<li><strong>Step 5</strong> Publish</li>
</ul>
</div>
</div>
<?php
		$queryMode = sprintf("SELECT display_mode FROM q_quizzes WHERE quiz_id = %d", $quiz->quiz_id);
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
		
//		$mode = "simple";
?>

<div id="create-quiz" class="frame rounded">

<?php if($mode == "simple" || $mode == "accurate"){ ?>
<form action="../modules/createQuizEngine.php?step=3" method="post"
	enctype="multipart/form-data" name="createQuiz" id="createQuiz"
	onsubmit="return submitCheckMulti(Spry.Widget.Form.validate(this));"><input
	type="hidden" name="id" value="<?php echo $quiz->quiz_id; ?>" /> <input
	type="hidden" name="optionCounts" id="optionCounts" value="" /> <input
	type="hidden" name="questionCount" id="questionCount" value="" />
<?php }else{ ?>
<form action="../modules/createQuizEngine.php?step=3" method="post"
	enctype="multipart/form-data" name="createQuiz" id="createQuiz"
	onsubmit="return submitCheckTest(Spry.Widget.Form.validate(this));"><input
	type="hidden" name="id" value="<?php echo $quiz->quiz_id; ?>" /> <input
	type="hidden" name="optionCounts" id="optionCounts" value="" /> <input
	type="hidden" name="questionCount" id="questionCount" value="" />
<?php } ?>	
	
<div id="createQuestionContainer">
<p id="questionTip" class="containerTip">Click on the "Add new question"
button to add a question entry!</p>
<?php if($mode == "simple" || $mode == "accurate"){ ?>
<body onload="QuizQuestionMulti.add()">
<?php }else{ ?>
<body onload="QuizQuestionTest.add()">
<?php } ?>
</div>

<?php if($mode == "simple" || $mode == "accurate"){ ?> 
<div class="add_container"><input type="submit" name="save" id="prev"
	value="Previous Step" />&nbsp; <input type="button"
	name="addQuestionBtn" id="addQuestionBtn" value="Add new question"
	onclick="QuizQuestionMulti.add()" />&nbsp; <input type="submit" name="save"
	id="next" value="Next Step!" /></div>
<?php }else{ ?>
<div class="add_container"><input type="submit" name="save" id="prev"
	value="Previous Step" />&nbsp; <input type="button"
	name="addQuestionBtn" id="addQuestionBtn" value="Add new question"
	onclick="QuizQuestionTest.add()" />&nbsp; <input type="submit" name="save"
	id="next" value="Next Step!" /></div>
<?php } ?>
	
</form>
</div>
		<?php // THE FOURTH STEP: Confirm and publish
		break; case 4:
		
		$queryMode = sprintf("SELECT display_mode FROM q_quizzes WHERE quiz_id = %d", $quiz->quiz_id);
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
		
		require("../modules/variables.php");

		// check the number of results
		if($mode == "simple" || $mode == "accurate"){
			$numResults = $quiz->getResultsMulti("count");
		}
		else{
			$numResults = $quiz->getResultsTest("count");
		}

		// check the number of questions
		$numQuestions = $quiz->getQuestions("count");
		// check the number of options
		$listQuestion = explode(',', $quiz->getQuestions());
		$totalOptions = 0;

		if($numQuestions != 0){
		$questionState = true;
		$optionState = true;
		foreach($listQuestion as $question){
		// check the number of options for this question
		if($mode == "simple" || $mode == "accurate"){
			$numOptions = $quiz->getOptionsMulti($question, "count");
		}
		else{
			$numOptions = $quiz->getOptionsTest($question, "count");
		}
		if($numOptions < $VAR_QUIZ_MIN_OPTIONS){
		$optionState = false;
		}
		$totalOptions += $numOptions;
		}
		}

		if($numQuestions != 0){
		$averageOptionCount = $totalOptions / $numQuestions;
		}else{
		$averageOptionCount = 0;
		}

		if($mode == "simple" || $mode == "accurate"){		
			if(!$quiz->checkPublishMulti()){
				$quizState = false;
			}else{
				$quizState = true;
			}
		}
		else{
			if(!$quiz->checkPublishTest()){
				$quizState = false;
			}else{
				$quizState = true;
			}
		}
		
?>
<div id="progress-container" class="framePanel rounded">
<h2>Create Quiz: Publish</h2>
<div class="content-container">
<p>You're just <strong>1</strong> step away from creating your own quiz!
The table below shows the review of your quiz.</p>
<ul class="rounded final">
	
	<li class="complete_full start"><strong>Step 1</strong> Quiz Type</li>
	<li class="complete_full"><strong>Step 2</strong> Quiz
	  Information</li>
	<li class="complete_full"><strong>Step 3</strong> Results</li>
	<li class="completed_last"><strong>Step 4</strong> Question</li>
	<li class="final"><strong>Step 5</strong> Publish</li>
</ul>
</div>
</div>
<div id="create-quiz" class="frame rounded">
<form action="../modules/createQuizEngine.php?step=4" method="post"
	name="createQuiz" id="createQuiz"><input type="hidden" name="id"
	value="<?php echo $quiz->quiz_id; ?>" />
<table border="0" align="center" cellpadding="5" cellspacing="0"
	id="checkQuizTable">
	<tr>
		<th scope="col">&nbsp;</th>
		<th scope="col">Count</th>
		<th scope="col">Remarks</th>
	</tr>
	<tr>
		<th>Results</th>
		<td align="center"><?php echo $numResults; ?></td>
		<td><?php if($numResults < $VAR_QUIZ_MIN_RESULT){ ?>You need at least
		<?php echo $VAR_QUIZ_MIN_RESULT; ?> results<?php }else{ ?>Ok!<?php } ?></td>
	</tr>
	<tr>
		<th>Question</th>
		<td align="center"><?php echo $numQuestions; ?></td>
		<td><?php if($numQuestions < $VAR_QUIZ_MIN_QUESTIONS){ ?>You need at
		least <?php echo $VAR_QUIZ_MIN_QUESTIONS; ?> question(s)<?php }else{ ?>Ok!<?php } ?></td>
	</tr>
	<tr>
		<th>Options</th>
		<td align="center">Avg. ~<?php echo sprintf("%.2f", $averageOptionCount); ?></td>
		<td><?php if(!$questionState){ ?>You do not have any questions<?php }else{ if(!$optionState){ ?>One
		of your questions has less than <?php echo $VAR_QUIZ_MIN_OPTIONS; ?>
		options!<?php }else{ ?>Ok!<?php }} ?></td>
	</tr>
</table>
<p><?php if($quizState){ ?> Congratuations! Your quiz has passed the
basic requirements. You can choose to preview your quiz first, or
publish your quiz now. <?php }else{ ?> Opps! It seems that your quiz
doesn't fulfill certain requirements. All quizzes require a minimum of <?php echo $VAR_QUIZ_MIN_RESULT; ?>
result(s) and <?php echo $VAR_QUIZ_MIN_QUESTIONS; ?> questions(s). Each
question also required at least <?php echo $VAR_QUIZ_MIN_OPTIONS; ?>
options. <?php } ?></p>
<table width="95%" border="0" align="center" cellpadding="5"
	cellspacing="0">
	<tr>
		<th scope="row"><input type="submit" name="save" id="prev"
			value="Previous Step" />&nbsp; <?php if(!$quizState){ ?><input
			type="submit" name="save" id="preview" value="Preview"
			class="btnDisabled" disabled="disabled" />&nbsp; <input type="submit"
			name="save" id="publish" value="Publish Now!" class="btnDisabled"
			disabled="disabled" /><?php }else{ ?><input type="submit" name="save"
			id="preview" value="Preview" />&nbsp; <input type="submit"
			name="save" id="publish" value="Publish Now!" /><?php } ?></th>
	</tr>
</table>
<input type="hidden" name="resultCount" id="resultCount" value="" /> <input
	type="hidden" name="questionCount" id="questionCount" value="" /> <input
	type="hidden" name="optionCounts" id="optionCounts" value="" /></form>
</div>
		<?php // THE FIRST STEP
break;}
}else{ ?>
<div id="takequiz-preamble" class="framePanel rounded">
  <h2>Opps, quiz not found!</h2>
  <div class="content-container"> <span class="logo"><img src="../webroot/img/quizroo-question.png" alt="Member not found" width="248" height="236" /></span>
    <p>Sorry! The quiz that you're looking for may no be available. Please check the ID of the quiz again.</p>
    <p>The reason you're seeing this error could be due to:</p>
    <ul>
      <li>The URL is incorrect or doesn't  contain the ID of the quiz</li>
      <li>No quiz with this ID exists</li>
      <li>The owner could have removed the quiz</li>
      <li>The quiz was taken down due to violations of  rules at Quizroo</li>
    </ul>
  </div>
</div>
<?php }}else{ 
// generate a one time hash key for the upload, (this hash key will stay with the quiz throughout the entire creation process)
$unikey = get_rand_id(8);
// bind it to a member
$member->bindImagekey($unikey);
// since it's a new quiz, state is always true
$quiz_state = true;

// populate the categories
$query_listCat = "SELECT cat_id, cat_name FROM q_quiz_cat";
$listCat = mysql_query($query_listCat, $quizroo) or die(mysql_error());
$row_listCat = mysql_fetch_assoc($listCat);
$totalRows_listCat = mysql_num_rows($listCat);
?>
  <form action="../modules/createQuizEngine.php?step=1" method="post" enctype="multipart/form-data" name="createQuiz" id="createQuiz" onsubmit="return submitCheck(Spry.Widget.Form.validate(this));">
<div id = "step_0">
<div id="progress-container" class="framePanel rounded">
<h2>Create Quiz: Choose Quiz Type</h2>
<div class="content-container">
<p>You're just <strong>5</strong> steps away from creating your own
quiz! <em>Step 1</em> is for you to choose your favourite quiz type and
how you want it to be displayed.</p>
<p>Please complete this step then move on to creating quiz process</p>
<ul class="rounded">
	<li class="current start"><strong>Step 1</strong> Quiz Type</li>
	<li><strong>Step 2</strong> Quiz Information</li>
	<li><strong>Step 3</strong> Results</li>
	<li><strong>Step 4</strong> Question</li>
	<li><strong>Step 5</strong> Publish</li>
</ul>
</div>
</div>

<div id="form_step0" class="framePanel rounded">
<table width="100%" style="font-size: 12px; font: Verdana, Arial, Helvetica, sans-serif">
	<!-- Test type-->
	<tr>
	  <td colspan="3"><h2><input id="test" name="type" type="radio" value="1" checked="checked"/>Test</h2></td>
	</tr>
	<tr>
		<td valign="top" width="40%">
                <div id = "blk-1">
				<table>
					<tr>
						<td>Do you want to customize your quiz result?<a
							href="customnizeTestExpl.php"> What's this?</a></td>
						<!-- To replace the hyperlink-->
					</tr>
					<tr>
						<td><input type="radio" value="test_custom" name="mode1" checked="checked"/>Yes</td>
					</tr>
					<tr>
						<td><input type="radio" value="test_simple" name="mode1" />No, keep it
						simple</td>
					</tr>
				</table>
                </div>		
          </td>
		<td width="60%">
		<div class="content-container">
		<p><strong>Quiz of Test type</strong>: designed to determine knowledge
		of a particular subject based on factual information of it. Hence,
		there are right and wrong answers for each question. Below is an
		example of Test type quiz.</p>
		<table>
			<tr>
				<td>
				<table>
					<tr bgcolor="#FC0">
						<td>Quiz title:</td>
						<td>How well do you know Michael Jackson?</td>
					</tr>
					<tr>
						<td>Question 1:</td>
						<td>Where is he from?</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><input type="radio" disabled="disabled" />
Thailand</td>
				    </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><input type="radio" disabled="disabled" />
The US (Correct
					  answer)</td>
				    </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><input type="radio" disabled="disabled" />
England</td>
				    </tr>
					<tr>
						<td>&nbsp;</td>
						<td>
						<input type="radio" disabled="disabled" />
						Singapore					  </td>
					</tr>
				</table>				</td>
			</tr>
		</table>
		</div>		
        </td>
	</tr>
	<!-- Personality type-->
	<tr>
	  <td colspan="3"><h2><input id="personality" name="type" type="radio" value="2"/>Personality</h2></td>
	  </tr>
	<tr>
		<td valign="top" width="40%">
                <div id = "blk-2" style="display:none">
				<table>
					<tr>
						<td>Do you want to make your quiz more accurate? <a
							href="customnizeMultiExpl.php">What's this?</a></td>
						<!-- To replace the hyperlink-->
					</tr>
					<tr>
						<td><input type="radio" value="multi_accurate" name="mode2" checked="checked"/>Yes</td>
					</tr>
					<tr>
						<td><input type="radio" value="multi_simple" name="mode2" />No, keep it
						simple</td>
					</tr>
				</table>
                </div>				
        </td>
		<td width="60%">
		<div class="content-container">
		<p><strong>Quiz of Personality type:</strong> consisting of questions
		whose purpose is to test on different aspects of a person's characters
		such as behaviors, thoughts and feelings. There is no right or wong
		answer and the result derives from how quiz takers choose their
		reactions in certain situation. Below is an example of Personality
		type quiz.</p>
		<table>
			<tr>
				<td>
				<table>
					<tr bgcolor="#FC0">
						<td width="58">Quiz title:</td>
						<td>How serious are you?</td>
					</tr>
					<tr>
						<td>Question 1:</td>
						<td>When you're with your friends you&hellip;</td>
					</tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><span class="style1">
					    <input type="radio" disabled="disabled" />
					    Make comments from
					  time to time. You aren't quiet or boisterous</span></td>
				    </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><span class="style1">
					    <input type="radio" disabled="disabled" />
					    You just listen to
					  what everyone else is talking about.</span></td>
				    </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><span class="style1">
					    <input type="radio" disabled="disabled" />
				      Always making jokes.</span></td>
				    </tr>
					<tr>
					  <td>&nbsp;</td>
					  <td><span class="style1">
					    <input type="radio" disabled="disabled" />
Don't care about
					  anything.</span></td>
				    </tr>
				</table>				
                </td>
				
			</tr>
		</table>
		</div>		
        </td>
        </tr>
    <tr>
    	<td></td>
        <td align="right"><input type="button" value="Next Step!" id="step0"/></td>
	</tr>
</table>
</div>
</div>
<div id = "step_1" style="display:none">
<div id="progress-container" class="framePanel rounded">
  <h2>Create Quiz</h2>
  <div class="content-container">
  <p>You're just <strong>4</strong> steps away from creating your own quiz! <em>Step 1</em> contains all the basic information we need to help you setup your quiz. If you have prepared several images for quiz, you can upload them all at once! You can choose which images to use at every step of the creation process.</p>
  <ul class="rounded">
    <li class="completed_last start"><strong>Step 1</strong> Quiz Type</li>
	<li class="current"><strong>Step 2</strong> Quiz Information</li>
	<li><strong>Step 3</strong> Results</li>
	<li><strong>Step 4</strong> Question</li>
	<li><strong>Step 5</strong> Publish</li>
  </ul>
  </div>
</div>
<div id="create-quiz" class="frame rounded">
    <input type="hidden" name="unikey" value="<?php echo $unikey; ?>" />
    <h4>Quiz Information</h4>
      <p>The Quiz Information allows you to tell a potential quiz taker what insights your quiz intends to deliver.</p>
      <table width="95%" border="0" align="center" cellpadding="5" cellspacing="0">
        <tr>
          <th width="120" valign="top" scope="row"><label for="quiz_title">Title</label></th>
          <td><span id="sprytextfield0" class="sprytextfield">
            <input type="text" name="quiz_title" id="quiz_title" />
          <span class="textfieldRequiredMsg">A title is required.</span></span> <span class="desc">Give your Quiz a meaningful title! Your title will be the first thing that catches a reader's attention.</span></td>
        </tr>
        <tr>
          <th width="120" valign="top" scope="row"><label for="quiz_description">Description</label></th>
          <td><span id="sprytextarea0" class="sprytextarea">
            <textarea name="quiz_description" id="quiz_description" cols="45" rows="5"></textarea>
            <span class="textareaRequiredMsg">Description should not be blank!</span></span><span class="desc">Provide a short description on what your quiz is about.</span></td>
        </tr>
        <tr>
          <th valign="middle" scope="row"><label for="quiz_cat">Topic</label></th>
          <td><select name="quiz_cat" id="quiz_cat">
              <?php do { ?>
              <option value="<?php echo $row_listCat['cat_id']?>"><?php echo $row_listCat['cat_name']?></option>
              <?php } while ($row_listCat = mysql_fetch_assoc($listCat));
			  $rows = mysql_num_rows($listCat);
			  if($rows > 0) {
				  mysql_data_seek($listCat, 0);
				  $row_listCat = mysql_fetch_assoc($listCat);
			  } ?>
            </select></td>
        </tr>
        <tr>
          <th rowspan="2" valign="top" scope="row"><label>Quiz Picture</label>
          <input type="hidden" name="result_picture_0" id="result_picture_0" value="" /></th>
          <td class="desc"><div id="swfupload-control-0" class="swfupload-control">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td><input name="uploader-0" type="button" id="uploader-0" /></td>
                  <td valign="middle" class="formDesc">Upload a new picture (jpg, gif or png); You can select more than 1 file!</td>
                </tr>
              </table>
              <table border="0" cellspacing="0" cellpadding="5">
                <tr>
                  <td><div id="selected-image-0" class="selected-image"></div></td>
                  <td><p id="queuestatus-0"></p></td>
                </tr>
              </table>
              <ol id="log-0" class="log">
              </ol>
            </div>
            <div id="pictureChoser_0"></div></td>
        </tr>
        <tr>
          <td class="desc"><div id="pictureChoser_0"></div></td>
        </tr>
        <tr>
          <th valign="top" scope="row">&nbsp;</th>
          <td align="right" class="desc"><input type="button" value='Previous Step' id="step1">&nbsp; <input type="submit" name="next" id="next" value="Next Step!" /></td>
        </tr>
      </table>
</div>
</div>
</form>
<?php mysql_free_result($listCat); } ?>
