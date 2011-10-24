<?php require('quizrooDB.php'); ?>
<?php
// get the member info
require('member.php');
require('quiz.php');
$member = new Member();

// turn on sessions
session_start();

if(isset($_POST['feedback'])){
	// Note: because the method is declared in quiz.php, an instance of quiz must be created
	    $quiz = new Quiz();
		$feedback_id = $quiz->createFeedback($_POST['feedback'], $member->id);
		header("Location: ../webroot/feedback.php?feedback_id=".$feedback_id);
}
?>