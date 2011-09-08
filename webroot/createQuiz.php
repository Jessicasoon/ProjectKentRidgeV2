<?php include("inc/header-php.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quizroo: Create Quiz</title>
<?php include("inc/header-css.php");?>
<link href="css/uploader.css" rel="stylesheet" type="text/css" />
<link href="css/createQuiz.css" rel="stylesheet" type="text/css" />
<link href="css/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="css/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
</head>

<body>
<div id="fb-root"></div>
<?php include("../modules/variables.php");?>
<?php include("../modules/statusbar.php");?>
<?php include("../modules/createQuizMain.php"); ?>
<?php include("inc/footer-js.php"); ?>
<script src="js/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="js/SpryValidationTextField.js" type="text/javascript"></script>
<script src="js/swf.upload.js"type="text/javascript"></script>
<script src="js/jquery.swfupload.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.9.js" type="text/javascript"></script>
<script type="text/javascript">
var unikey = "<?php echo $unikey ?>";
</script>
<script src="js/Quiz.create.js" type="text/javascript"></script>
<script src="js/swf.multi-uploader.js" type="text/javascript"></script>
<?php if(isset($quiz_state)){ if($quiz_state){?>
<script type="text/javascript">
$(document).ready(function(){
	// init the validators
	QuizValidate.init();
	<?php if(isset($_GET['step'])){ 
			if($_GET['step'] == 0){
	?>
	QuizInfo.init(<?php echo $quiz->quiz_id; ?>, '<?php echo $unikey; ?>');
	<?php switch($_GET['step']){ case 1: ?>
	scanInitUploader();
	<?php break; case 2: ?>	
	
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
	QuizResultMulti.init();
<?php }else{ ?>
	QuizResultTest.init();
<?php } ?>	

	<?php break; case 3: ?>
	
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
	QuizQuestionMulti.init();
<?php }else{ ?>
	QuizQuestionTest.init();
<?php } ?>	
	
	
	<?php break; case 4: ?>
	<?php }}}else{ ?>
	initUploader(0);
	<?php } ?>
});
</script>
<?php }} ?>
</body>
</html>