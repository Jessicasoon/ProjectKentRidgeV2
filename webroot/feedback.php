<?php include("inc/header-php.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Quizroo: Create Quiz</title>
<?php include("inc/header-css.php");?>
<link href="css/dashboard.css" rel="stylesheet" type="text/css" />
<link href="css/recent.css" rel="stylesheet" type="text/css" />
<link href="css/uploader.css" rel="stylesheet" type="text/css" />
<link href="css/createQuiz.css" rel="stylesheet" type="text/css" />
<link href="css/SpryValidationTextField.css" rel="stylesheet" type="text/css" />
<link href="css/SpryValidationTextarea.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="fb-root"></div>
<?php include("../modules/variables.php");?>
<?php include("inc/footer-js.php"); ?>
<script src="js/SpryValidationTextarea.js" type="text/javascript"></script>
<script src="js/SpryValidationTextField.js" type="text/javascript"></script>
<script src="js/swf.upload.js"type="text/javascript"></script>
<script src="js/jquery.swfupload.js" type="text/javascript"></script>
<script src="js/jquery-ui-1.8.9.js" type="text/javascript"></script>
<script src="js/Quiz.create.js" type="text/javascript"></script>
<div class="framePanel">
<h2 align="center"><span style="color:#C00; font-family: Myriad Pro, Arial, sans-serif; font-weight: bold;">Quizroo Feedback Portal</span></h2>
<?php if(!isset($_GET['feedback_id'])) { ?>
<p>Type your feedback in this textbox and send it to us.</p>
<form action="../modules/feedback.php" method="post" enctype="multipart/form-data" name="feedback" id="feedback" onSubmit="return submitCheck(Spry.Widget.Form.validate(this));">
<textarea name="feedback" id="feedback" cols="80" rows="5"></textarea>
<span class="textareaRequiredMsg">Feedback should not be blank!</span>
<br/><br/>
<input type="submit" name="submit" id="submit" value="Submit!"/>
</form>
<?php } else if (isset($_GET['feedback_id'])){ ?>
<p>Thank you very much for your valuable feedback on Quizroo. Your feedback has been submitted sucessfully.
We will take your feedback into account and do necessary changes accordingly.</p>
<?php } ?>
</div>
</body>
</html>