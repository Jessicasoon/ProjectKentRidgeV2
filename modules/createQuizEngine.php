<?php require('quizrooDB.php'); ?>
<?php
// get the member info
require('member.php');
require('quiz.php');
$member = new Member();

// turn on sessions
session_start();

// find out which step is it
if(isset($_GET['step'])){
	switch($_GET['step']){
	//ADDED BY HIEN
		case 0:

		$type = $_POST['type']; //get from form
		$mode = $_POST['mode']; // get from form in createQuizMain
		if($type == "1" && $mode == "test_simple")
			header("Location: ../webroot/createQuiz.php?step=1&type=1a");
		else if ($type == "1" && $mode == "test_custom")
			header("Location: ../webroot/createQuiz.php?step=1&type=1b");
		else if ($type == "2" && $mode == "multi_simple")
			header("Location: ../webroot/createQuiz.php?step=1&type=2a");
		else if ($type == "2" && $mode == "multi_accurate")
			header("Location: ../webroot/createQuiz.php?step=1&type=2b");
		break;
		
		case 1: // save the quiz information
		
		// get the unikey from the form
		$key = $_POST['unikey'];
		$type = $_GET['type']; 
		
		// save the data from step 1
		$quiz_picture = ($_POST['result_picture_0'] != "") ? $_POST['result_picture_0'] : "none.gif";
		if(isset($_POST['id'])){
			$quiz = new Quiz($_POST['id']);
			$quiz_id = $quiz->update($_POST['quiz_title'], $_POST['quiz_description'], $_POST['quiz_cat'], $quiz_picture, $member->id);
		}else{
			$quiz = new Quiz();
			$q_mode = " ";
			$q_type = 0;
			if($type == "1a"){ 
				$q_mode = "test_simple"; 
				$q_type = 1; 
			}
			else if($type == "1b"){ 
				$q_mode = "test_custom"; 
				$q_type = 1; 
			}
			else if($type == "2a"){
				$q_mode = "multi_simple"; 
				$q_type = 2; 
			}
			else if($type == "2b"){ 
				$q_mode = "multi_accurate"; 
				$q_type = 2; 
			}
			//function createQuiz($title, $description, $cat, $picture, $member_id, $key, $display_mode, $fk_quiz_type) from quiz.php			
			$quiz_id = $quiz->createQuiz($_POST['quiz_title'], $_POST['quiz_description'], $_POST['quiz_cat'], $quiz_picture, $member->id, $key, $q_mode, $q_type);
		}
		
		// direct them to step 2
		header("Location: ../webroot/createQuiz.php?step=2&id=".$type);
		
		break;		
		case 2: // save the quiz results
		
		// get the id from the form
		$quiz_id = $_POST['id'];

		//***********************************************ADD BY LIEN************************************************//
		$queryMode = sprintf("SELECT display_mode FROM q_quizzes WHERE quiz_id = %d", $quiz_id);
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

		//***********************************************END OF ADD BY LIEN************************************************//
		
		// save the results from step 2
		$quiz = new Quiz($quiz_id);
		// Quiz Results

		if($mode == "simple" || $mode == "accurate"){
			for($i = 0; $i < $_POST['resultCount']; $i++){
				if(isset($_POST['result_title_'.$i]) && isset($_POST['result_description_'.$i]) && isset($_POST['result_picture_'.$i])){
					$result_title = $_POST['result_title_'.$i];
					$result_description = $_POST['result_description_'.$i];
					$result_picture = ($_POST['result_picture_'.$i] != "") ? $_POST['result_picture_'.$i] : "none.gif";
					if(isset($_POST['ur'.$i])){
						$quiz->updateResultMulti($result_title, $result_description, $result_picture, $_POST['ur'.$i], $member->id);
					}else{
						$quiz->addResultMulti($result_title, $result_description, $result_picture, $member->id);
					}
				}
			}
		}
		else{ //HAVE TO CHANGE FOR TEST BY YL
			for($i = 0; $i < $_POST['resultCount']; $i++){
				if(isset($_POST['result_title_'.$i]) && isset($_POST['result_description_'.$i]) && isset($_POST['result_picture_'.$i])){
					$result_title = $_POST['result_title_'.$i];
					$result_description = $_POST['result_description_'.$i];
					$result_picture = ($_POST['result_picture_'.$i] != "") ? $_POST['result_picture_'.$i] : "none.gif";
					if(isset($_POST['ur'.$i])){
						$quiz->updateResultTest($result_title, $result_description, $result_picture, $_POST['ur'.$i], $member->id);
					}else{
						$quiz->addResultTest($result_title, $result_description, $result_picture, $member->id);
					}
				}
			}		
		}
		
		// check the direction to go
		if($_POST['save'] == "Previous Step"){
			header("Location: ../webroot/createQuiz.php?step=1&id=".$quiz_id);
		}else{
			header("Location: ../webroot/createQuiz.php?step=3&id=".$quiz_id);
		}
		
		break;
		case 3:
		
		// get the id from the form
		$quiz_id = $_POST['id'];

		//***********************************************ADD BY LIEN************************************************//
		$queryMode = sprintf("SELECT display_mode FROM q_quizzes WHERE quiz_id = %d", $quiz_id);
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

		//***********************************************END OF ADD BY LIEN************************************************//
		
		// save the questions from step 3
		$quiz = new Quiz($quiz_id);

		if($mode == "simple" || $mode == "accurate"){		
			require("../modules/variables.php");
			$numResults = $quiz->getResultsMulti("count"); //function in quiz.php
			// Insert the questions and options
			$questionArray = explode("_", $_POST['optionCounts']);
			//array explode ( string $delimiter , string $string [, int $limit ] )
			//explode a string by the delimitor to array

			$question = array();
			for($i = 0; $i < $_POST['questionCount']; $i++){
				if(isset($_POST['question_'.$i])){
					if(isset($_POST['uq'.$i])){ // uq:updateQuestion
						$question_id = $quiz->updateQuestion($_POST['question_'.$i], $_POST['uq'.$i], $member->id);
					}else{
						$question_id = $quiz->addQuestion($_POST['question_'.$i], $member->id);
					}
				}
				if ($mode == "simple") //THIS LINE ADDED BY LIEN
				{
					for($j = 0; $j < $questionArray[$i]; $j++){
						if(isset($_POST['q'.$i.'o'.$j]) && isset($_POST['q'.$i.'r'.$j]) ){
							if(isset($_POST['uq'.$i.'o'.$j])){
								$quiz->updateOptionMulti($_POST['q'.$i.'o'.$j], $_POST['q'.$i.'r'.$j], 1 , $_POST['uq'.$i.'o'.$j], $member->id); //default of weightage = 1
							}else{
								$quiz->addOptionMulti($_POST['q'.$i.'o'.$j], $_POST['q'.$i.'r'.$j], 1 , $question_id, $member->id); //default of weightage = 1
							}
						}
					}
		
				} // end if quiz mode = simple
				if ($mode == "accurate") //ENTIRE CHUNK ADDED BY LIEN
				{			
					for($j = 0; $j < $questionArray[$i]; $j++){
						if(isset($_POST['q'.$i.'o'.$j])  ){
							for ($k = 0; $k < $numResults; $k++) {
								if(isset($_POST['uq'.$i.'o'.$j])){
									//function from quiz.php: updateOption($option, $result, $weightage, $option_id, $memberID)
									//$quiz->updateOption($_POST['q'.$i.'o'.$j], $_POST['q'.$i.'r'.$j], $_POST['q'.$i.'w'.$j], $_POST['uq'.$i.'o'.$j], $member->id);
									$quiz->updateOptionMulti($_POST['q'.$i.'o'.$j], $_POST['q'.$i.'r'.$k], $_POST['q'.$i.'o'.$j.'w'.$k], $_POST['uq'.$i.'o'.$j], $member->id);
									
								}else{
									//function from quiz.php: addOption($option, $result, $weightage, $question, $memberID)
									$quiz->addOptionMulti($_POST['q'.$i.'o'.$j], $_POST['q'.$i.'r'.$k], $_POST['q'.$i.'o'.$j.'w'.$k], $question_id, $member->id);
								}
							}// end for k loop
						} 
					} // end for j loop
		
				} // END OF CHUNK ADDED BY LIEN
			}
		}else{ //MODE FOR TEST HAVE TO CHANGE BY YL
			// Insert the questions and options
			$questionArray = explode("_", $_POST['optionCounts']);

			$question = array();
			for($i = 0; $i < $_POST['questionCount']; $i++){
				if(isset($_POST['question_'.$i])){
					if(isset($_POST['uq'.$i])){
						$question_id = $quiz->updateQuestion($_POST['question_'.$i], $_POST['uq'.$i], $member->id);
					}else{
						$question_id = $quiz->addQuestion($_POST['question_'.$i], $member->id);
					}
				}
	//EDITED BY YL 2sep removed weightage for test
				// Quiz Options
				for($j = 0; $j < $questionArray[$i]; $j++){
					if(isset($_POST['q'.$i.'o'.$j]) && isset($_POST['q'.$i.'r'.$j])){
						if(isset($_POST['uq'.$i.'o'.$j])){
							$quiz->updateOptionTest($_POST['q'.$i.'o'.$j], $_POST['q'.$i.'r'.$j], $_POST['uq'.$i.'o'.$j], $member->id);
						}else{
							$quiz->addOptionTest($_POST['q'.$i.'o'.$j], $_POST['q'.$i.'r'.$j], $question_id, $member->id);
						}
					}
					elseif(isset($_POST['q'.$i.'o'.$j])){
						if(isset($_POST['uq'.$i.'o'.$j])){ 
							$quiz->updateOptionTest($_POST['q'.$i.'o'.$j], 0, $_POST['uq'.$i.'o'.$j], $member->id);
						}else{
							$quiz->addOptionTest($_POST['q'.$i.'o'.$j], 0, $question_id, $member->id);
						}
					} //end else
				} //end for loop
			}
		}//END MODE ELSE
		
		// check the direction to go
		if($_POST['save'] == "Previous Step"){
			header("Location: ../webroot/createQuiz.php?step=2&id=".$quiz_id);
		}else{
			header("Location: ../webroot/createQuiz.php?step=4&id=".$quiz_id);
		}
		
		break;
		case 4: // final step
		
		// get the id from the form
		$quiz_id = $_POST['id'];
		
		// check the direction to go
		if($_POST['save'] == "Previous Step"){
			header("Location: ../webroot/createQuiz.php?step=3&id=".$quiz_id);
		}elseif($_POST['save'] == "Preview"){
			header("Location: ../webroot/createQuizSuccess.php?id=".$quiz_id);
		}else{
			header("Location: ../webroot/publishQuiz.php?id=".$quiz_id);
		}

		break;
	}
}
?>
