<!-- Read Excel file and add to database-->
<html>
<style type="text/css">
<!--
.style1 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-weight: bold;
	font-size: 24px;
}
.style5 {color: #990066}
.style10 {color: #000099}
.style15 {color: #000000; font-size: 14px; }
.style16 {color: #FF0000}
-->
</style>
<body>
<div class="framePanel rounded">
<p align="center" class="style1 style1"> Preview Uploaded Quiz </p></div>
<div id="takequiz-preview" class="frame rounded">
<table bordercolor="#000000">

 <?php
require('../modules/quizrooDB.php'); // database connection
// get the member info
require('../modules/member.php');
$member = new Member();
require('../modules/quiz.php');
?>
  <?php
// get the unikey from the form
$key = $_POST['unikey'];

if ( $_FILES['file']['tmp_name'] )
{
 $dom = DOMDocument::load( $_FILES['file']['tmp_name'] );
 $rows = $dom->getElementsByTagName( 'Row' );
 $first_row = true;
 $quiz_id = 0;
 if(strcmp($_POST['type'], "test") == 0) {
 foreach ($rows as $row)
 {
   if ( !$first_row ) // for checking whether it is the header row
   {
     // for quiz information
     $title = "";
     $des = "";
     $cat = "";
     $quiz_picture = "";
	 $memberid = "";
	 $display_mode = "";
	 // for question
	 $question = "";
	 $question_picture = "";
	 // for option
	 $option = "";
	 $isCorrect = "";
	 // for result
	 $result_title = "";
	 $result_des = "";
	 $result_picture = "";
	 $range_max = "";
	 $range_min = "";
	 
     $index = 1;
     $cells = $row->getElementsByTagName( 'Cell' );
     foreach( $cells as $cell )
     {
       $ind = $cell->getAttribute( 'ss:Index' );
       if ( $ind != null ) $index = $ind;

       if ( $index == 1 ) $title = $cell->nodeValue;
	   //check if title is null
	   if($title != null) {
	   
		   // if title is not null, get quiz info
		   if ( $index == 2 ) $des = $cell->nodeValue;
		   if ( $index == 3 ) $cat = $cell->nodeValue;
		   if ( $index == 4 ) $quiz_picture = $cell->nodeValue;
		   if ( $index == 5 ) $display_mode = $cell->nodeValue;
		   if ( $index == 6 ) $memberid = $cell->nodeValue;
	   }
	   else{
		   if ( $index == 7 ) $question = $cell->nodeValue;
		   if ( $index == 8 ) $question_picture = $cell->nodeValue;
		   if ( $index == 9 ) $option = $cell->nodeValue;
		   if ( $index == 10 ) $isCorrect = $cell->nodeValue;
		   if ( $index == 11 ) $result_title = $cell->nodeValue;
		   if ( $index == 12 ) $result_des = $cell->nodeValue;
		   if ( $index == 13 ) $result_picture = $cell->nodeValue;
		   if ( $index == 14 ) $range_max = $cell->nodeValue;
		   if ( $index == 15 ) $range_min = $cell->nodeValue;
	   }
	   $index += 1;
	 } // end going through cells
	 
	 if($title != null) {
	 ?>
  <tr bgcolor="#FFCC00">
    <td><span class="style15">Quiz title: </span></td>
    <td><?php echo($title); ?></td>
  </tr>
  <tr>
    <td><span class="style15">Quiz description: </span></td>
    <td><?php echo($des); ?></td>
  </tr>
  <tr>
    <td><span class="style15">Quiz category: </span></td>
    <td><?php echo($cat); ?></td>
  </tr>
  <tr>
    <td><span class="style15">Quiz picture: </span></td>
    <td><?php echo($quiz_picture); ?></td>
  </tr>
  <tr>
    <td><span class="style15">Display mode: </span></td>
    <td><?php echo($display_mode); ?></td>
  </tr>
  <?php
	 //create quiz
	 $quiz = new Quiz();
	 //get the quiz_id here to use for inserting questions
	 $quiz_id = $quiz->createQuiz($title, $des, $cat, $quiz_picture, $memberid, 'Admin', $display_mode, 1);
	 }
	 
	 //check if the question is null. If not, insert into database.
	 if($question != null){
	 ?>
  <tr bgcolor="#FFCCFF">
    <td><span class="style5">Question:</span></td>
    <td><?php echo($question); ?></td>
  </tr>
  <?php
	 	$question_id = $quiz->addQuestion($question, $memberid);
	 }
	 //check if the option is null. If not, insert into database with reference
	 if($option != null){
	 ?>
  <tr>
    <td align="right"><span class="style5"><input type="radio" disabled="disabled"/></span></td>
    <td><?php echo($option); ?></td>
    <td><span class="style5">Is correct?</span></td>
    <td><?php echo($isCorrect); ?></td>
  </tr>
  <?php
	 	$quiz->addTestTypeOption($option, $isCorrect, $question_id);
	 }
	 //check if result is null. If not, insert into database.
	 if($result_title != null){
	 ?>
  <tr bgcolor="#99CCFF">
    <td><span class="style10">Result:</span></td>
    <td><?php echo($result_title); ?></td>
  </tr>
  <tr>
    <td><span class="style10">Result description:</span></td>
    <td><?php echo($result_des); ?></td>
  </tr>
  <tr>
    <td><span class="style10">Result picture:</span></td>
    <td><?php echo($result_picture); ?></td>
  </tr>
  <tr>
    <td><span class="style10">Result upper bound:</span></td>
    <td><?php echo($range_max); ?></td>
  </tr>
  <tr>
    <td><span class="style10">Result lower bound:</span></td>
    <td><?php echo($range_min); ?></td>
  </tr>
  <?php
	 	$quiz->addTestTypeResult($result_title, $result_des, $result_picture, $range_max, $range_min, $quiz_id);
	 }
     } // end if first row
	 $first_row = false;
   } // end going through 1 row
   } // end if test type
   else if (strcmp($_POST['type'], "personality") == 0) {
   foreach ($rows as $row)
 {
   if ( !$first_row ) // for checking whether it is the header row
   {
     // for quiz information
     $title = "";
     $des = "";
     $cat = "";
     $quiz_picture = "";
	 $memberid = "";
	 $display_mode = "";
	 // for question
	 $question = "";
	 $question_picture = "";
	 // for option
	 $option = "";
	 $result_t = "";
	 $result_d = "";
	 $point = "";
	 // for result
	 $result_title = "";
	 $result_des = "";
	 $result_picture = "";
	 
     $index = 1;
     $cells = $row->getElementsByTagName( 'Cell' );
     foreach( $cells as $cell )
     {
       $ind = $cell->getAttribute( 'ss:Index' );
       if ( $ind != null ) $index = $ind;

       if ( $index == 1 ) $title = $cell->nodeValue;
	   //check if title is null
	   if($title != null) {
	   
		   // if title is not null, get quiz info
		   if ( $index == 2 ) $des = $cell->nodeValue;
		   if ( $index == 3 ) $cat = $cell->nodeValue;
		   if ( $index == 4 ) $quiz_picture = $cell->nodeValue;
		   if ( $index == 5 ) $display_mode = $cell->nodeValue;
		   if ( $index == 6 ) $memberid = $cell->nodeValue;
	   }
	   else{
		   if ( $index == 7 ) $question = $cell->nodeValue;
		   if ( $index == 8 ) $question_picture = $cell->nodeValue;
		   if ( $index == 9 ) $option = $cell->nodeValue;
		   if ( $index == 10 ) $result_t = $cell->nodeValue;
		   if ( $index == 11 ) $result_d = $cell->nodeValue;
		   if ( $index == 12 ) $point = $cell->nodeValue;
		   if ( $index == 13 ) $result_title = $cell->nodeValue;
		   if ( $index == 14 ) $result_des = $cell->nodeValue;
		   if ( $index == 15 ) $result_picture = $cell->nodeValue;
	   }
	   $index += 1;
	 } // end going through cells
	 
	 if($title != null) {
	 ?>
  <tr bgcolor="#FFCC00">
    <td><span class="style15">Quiz title: </span></td>
    <td><?php echo($title); ?></td>
  </tr>
  <tr>
    <td><span class="style15">Quiz description: </span></td>
    <td><?php echo($des); ?></td>
  </tr>
  <tr>
    <td><span class="style15">Quiz category: </span></td>
    <td><?php echo($cat); ?></td>
  </tr>
  <tr>
    <td><span class="style15">Quiz picture: </span></td>
    <td><?php echo($quiz_picture); ?></td>
  </tr>
   <tr>
    <td><span class="style15">Display mode:</span></td>
    <td><?php echo($display_mode); ?></td>
  </tr>
  <?php
	 //create quiz
	 $quiz = new Quiz();
	 //get the quiz_id here to use for inserting questions
	 $quiz_id = $quiz->createQuiz($title, $des, $cat, $quiz_picture, $memberid, 'Admin', $display_mode, 2);
	 }
	 
	 //check if the question is null. If not, insert into database.
	 if($question != null){
	 ?>
  <tr bgcolor="#FFCCFF">
    <td><span class="style5">Question:</span></td>
    <td><?php echo($question); ?></td>
  </tr>
  <?php
	 	$question_id = $quiz->addQuestion($question, $memberid);
	 }
	 //check if the option is null. If not, insert into database with reference
	 if($option != null){
	 ?>
  <tr>
    <td align="right"><span class="style5"><input type="radio" disabled="disabled"/></span></td>
    <td><?php echo($option); ?></td>
    <td><span class="style5">Result contributed to? </span></td>
    <td><?php echo($result_t); ?></td><td><?php echo($result_d); ?></td>
    <td><span class="style5">Option Weightage: </span></td>
    <td><?php echo($point); ?></td>
  </tr>
  <?php
	 	$quiz->addMultiTypeOption($option, $result_t, $result_d, $point, $question_id);
	 }
	 //check if result is null. If not, insert into database.
	 if($result_title != null){
	 ?>
  <tr bgcolor="#99CCFF">
    <td><span class="style10">Result:</span></td>
    <td><?php echo($result_title); ?></td>
  </tr>
  <tr>
    <td><span class="style10">Result description:</span></td>
    <td><?php echo($result_des); ?></td>
  </tr>
  <tr>
    <td><span class="style10">Result picture:</span></td>
    <td><?php echo($result_picture); ?></td>
  </tr>
  <?php
	 	$quiz->addMultiTypeResult($result_title, $result_des, $result_picture, $quiz_id);
	 }
     } // end if first row
	 $first_row = false;
   } // end going through 1 row
   } // end if personality type
   }
?>
</table>
</div>
<div align="center">
<form action="uploadingQuiz.php?file=<?php echo $_FILES['file']['tmp_name']; ?>" method="post">
  <input type="button" onClick="location.href='admin.php'" value='Back to admin page'>
  <input name="Confirm" type="submit" value="Confirm">
</form>
</div>
<div align="center" class="style16">
Please note that quiz database will be updated right after you click "Confirm". <br />
Hence, please check your quizzes carefully.
</div>
</body>
</html>
