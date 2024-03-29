<?php // get result number
if(isset($_GET['load'])){
	$unikey = $_GET['unikey'];
	require('quizrooDB.php');
	
	$query = sprintf("SELECT result_id, result_title, result_description, result_picture, range_max, range_min FROM q_results_test WHERE fk_quiz_id = %d", GetSQLValueString($_GET['id'], "int"));
	$getQuery = mysql_query($query, $quizroo) or die(mysql_error());
	$row_getQuery = mysql_fetch_assoc($getQuery);
	$totalRows_getQuery = mysql_num_rows($getQuery);
	
	$result = 0;
	if($totalRows_getQuery > 0){
		do{ $count = 1;
?>


	
<div id="r<?php echo $result; ?>" class="resultWidget">
<input type="hidden" name="ur<?php echo $result; ?>" id="ur<?php echo $result; ?>" value="<?php echo $row_getQuery['result_id']; ?>" />
<table width="95%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th colspan="2" valign="top" scope="row"><a href="javascript:;" onclick="QuizResultTest.remove(<?php echo $result; ?>);"><img src="img/delete.png" alt="" width="16" height="16" border="0" align="absmiddle" title="Remove" /></a> Result</th>
  </tr>
  <tr>
    <th width="120" valign="top" scope="row"><label for="result_title_<?php echo $result; ?>">Title</label></th>
    <td><span id="sprytextfield-result_title_<?php echo $result; ?>" class="sprytextfield"><input type="text" name="result_title_<?php echo $result; ?>" id="result_title_<?php echo $result; ?>" value="<?php echo $row_getQuery['result_title']; ?>" /><span class="textfieldRequiredMsg">A value is required.</span></span>
    <span class="desc">Provide a title for this result!</span></td>
  </tr>
  <tr>
    <th width="120" valign="top" scope="row"><label for="result_description_<?php echo $result; ?>">Description</label></th>
    <td><span id="sprytextarea-result_description_<?php echo $result; ?>" class="sprytextarea"><textarea name="result_description_<?php echo $result; ?>" id="result_description_<?php echo $result; ?>" cols="45" rows="5"><?php echo $row_getQuery['result_description']; ?></textarea><span class="textareaRequiredMsg">Description should not be blank!</span></span>
    <span class="desc">Tell the quiz taker what this result means</span></td>
  </tr>
  
  <tr> <!--result range-->
  <th width="120" valign="top" scope="row"><label>Range</label></th>
  <td><select name="result_minimum_<?php echo $result; ?>" id="result_minimum_<?php echo $result; ?>">
          <option value="select">Select</option>
		  <option value="0"<?php if(0 == $row_getQuery['range_min']){ echo ' selected = "selected"'; } ?>>&nbsp;&nbsp;&nbsp;0%&nbsp;&nbsp;&nbsp;</option>
          <option value="10"<?php if(10 == $row_getQuery['range_min']){ echo ' selected = "selected"'; } ?>>&nbsp;&nbsp;&nbsp;10%&nbsp;&nbsp;&nbsp;</option>
          <option value="20"<?php if(20 == $row_getQuery['range_min']){ echo ' selected = "selected"'; } ?>>&nbsp;&nbsp;&nbsp;20%&nbsp;&nbsp;&nbsp;</option>
		  <option value="30"<?php if(30 == $row_getQuery['range_min']){ echo ' selected = "selected"'; } ?>>&nbsp;&nbsp;&nbsp;30%&nbsp;&nbsp;&nbsp;</option>
		  <option value="40"<?php if(40 == $row_getQuery['range_min']){ echo ' selected = "selected"'; } ?>>&nbsp;&nbsp;&nbsp;40%&nbsp;&nbsp;&nbsp;</option>
          <option value="50"<?php if(50 == $row_getQuery['range_min']){ echo ' selected = "selected"'; }?>>&nbsp;&nbsp;&nbsp;50%&nbsp;&nbsp;&nbsp;</option>
          <option value="60"<?php if(60 == $row_getQuery['range_min']){ echo ' selected = "selected"'; }?>>&nbsp;&nbsp;&nbsp;60%&nbsp;&nbsp;&nbsp;</option>
          <option value="70"<?php if(70 == $row_getQuery['range_min']){ echo ' selected = "selected"'; } ?>>&nbsp;&nbsp;&nbsp;70%&nbsp;&nbsp;&nbsp;</option>
          <option value="80"<?php if(80 == $row_getQuery['range_min']){ echo ' selected = "selected"'; } ?>>&nbsp;&nbsp;&nbsp;80%&nbsp;&nbsp;&nbsp;</option>
          <option value="90"<?php if(90 == $row_getQuery['range_min']){ echo ' selected = "selected"'; } ?>>&nbsp;&nbsp;&nbsp;90%&nbsp;&nbsp;&nbsp;</option>
          <option value="100"<?php if(100 == $row_getQuery['range_min']){ echo ' selected = "selected"'; } ?>>&nbsp;&nbsp;&nbsp;100%&nbsp;&nbsp;</option>
      </select><span class="textareaRequiredMsg">Please select a value for this result range!</span></span>
    <span class="desc">Select the MINIMUM percentage range of correct answers you want the users to get to achieve this result</span></td>
  <!-- Modify on 13 Oct for change slider to select box-->
  <!--<script>QuizResultTest.slider();</script>
  
<div class="slider">

	<span class="amount"></span> 

      
      <!-- // might help in getting value for next result starting % - LIEN
      <span>Start:
        <span id="start"></span>
    </span>
    <span>
        <span id="delta"></span>
    </span>
     //end of might-help
    
      <div class="slider-range"></div> 
      <?php //$what = 21  ;// testing purpose,along with next line - LIEN ?>
      <input class="lowerbound" type="hidden" name="lowerbound" value="<?php //echo $what ?>" />
 </div>
 <span class="desc">Select the percentage range of correct answers you want the users to get to achieve this result.</span>
 <tr></tr>-->
 </tr> 
  
  <tr>
    <th width="120" rowspan="4" valign="top" scope="row"><label>Picture</label>
      <input name="result_picture_<?php echo $result; ?>" type="hidden" id="result_picture_<?php echo $result; ?>" value="<?php echo $row_getQuery['result_picture']; ?>" /></th>
    <td><div id="swfupload-control-<?php echo $result; ?>" class="swfupload-control">
      <script>initUploader("result_picture_<?php echo $result; ?>")</script>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input name="uploader-<?php echo $result; ?>" type="button" id="uploader-<?php echo $result; ?>" /></td>
          <td valign="middle" class="formDesc">Upload a new picture (jpg, gif or png); You can select more than 1 file!</td>
          </tr>
    </table>
<table border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><div id="selected-image-<?php echo $result; ?>" class="selected-image"></div></td>
    <td><p id="queuestatus-<?php echo $result; ?>"></p></td>
  </tr>
</table>
      <ol id="log-<?php echo $result; ?>" class="log">
      </ol>
    </div></td>
  </tr>
  <tr>
    <td><div id="pictureChoser_<?php echo $result; ?>"><?php if(sizeof(glob("../quiz_images/".$unikey."*")) > 0){ ?><table border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td><span class="formDesc">OR click on a picture below to use it as the result picture</span></td>
  </tr>
  <tr>
    <td><?php // return uploaded images
	if($unikey != ""){ foreach(glob("../quiz_images/".$unikey."*") as $filename){ ?>
		<a href="javascript:;" onClick="selectImage(<?php echo $result; ?>, '<?php echo str_replace("'", "\\'", basename($filename)); ?>')"><img src="../quiz_images/imgcrop.php?w=80&h=60&f=<?php echo basename($filename); ?>" width="80" height="60" id="r<?php echo $result; ?>i<?php echo $count; ?>" class="selectImage"></a>
	<?php $count++; }} ?>
	</td>
  </tr>
</table><?php } ?></div></td>
  </tr>
</table>
</div>			
<?php 	$result++;
		}while($row_getQuery = mysql_fetch_assoc($getQuery));
	}
}elseif(isset($_GET['delete'])){
	// delete the result
	require('member.php');
	require('quiz.php');
	
	// also pass in the member id for security check
	$quiz = new Quiz($_GET['id']);
	$member = new Member();
	if(!$quiz->removeResultTest($_GET['result'], $member->id)){
		echo "Delete not authorized";
	}
}else{
$result = $_GET['resultNumber'];
$unikey = $_GET['unikey'];
$count = 1;
?>
<div id="r<?php echo $result; ?>" class="resultWidget">
<table width="95%" border="0" align="center" cellpadding="5" cellspacing="0">
  <tr>
    <th colspan="2" valign="top" scope="row"><a href="javascript:;" onclick="QuizResultTest.remove(<?php echo $result; ?>);"><img src="img/delete.png" alt="" width="16" height="16" border="0" align="absmiddle" title="Remove" /></a> Result</th>
  </tr>
  <tr>
    <th width="120" valign="top" scope="row"><label for="result_title_<?php echo $result; ?>">Title</label></th>
    <td><span id="sprytextfield-result_title_<?php echo $result; ?>" class="sprytextfield"><input type="text" name="result_title_<?php echo $result; ?>" id="result_title_<?php echo $result; ?>" /><span class="textfieldRequiredMsg">A value is required.</span></span>
    <span class="desc">Provide a title for this result!</span></td>
  </tr>
  <tr>
    <th width="120" valign="top" scope="row"><label for="result_description_<?php echo $result; ?>">Description</label></th>
    <td><span id="sprytextarea-result_description_<?php echo $result; ?>" class="sprytextarea"><textarea name="result_description_<?php echo $result; ?>" id="result_description_<?php echo $result; ?>" cols="45" rows="5"></textarea><span class="textareaRequiredMsg">Description should not be blank!</span></span>
    <span class="desc">Tell the quiz taker what this result means</span></td>
  </tr>
  

	
	   <tr> <!--result range-->
  <th width="120" valign="top" scope="row"><label>Range</label></th>
  <!-- Modifies on 13 Oct for changing slider to range-->
  <td><select name="result_minimum_<?php echo $result; ?>" id="result_minimum_<?php echo $result; ?>">
          <option value="select">Select</option>
		  <option value="0">&nbsp;&nbsp;&nbsp;0%&nbsp;&nbsp;&nbsp;</option>
          <option value="10">&nbsp;&nbsp;&nbsp;10%&nbsp;&nbsp;&nbsp;</option>
          <option value="20">&nbsp;&nbsp;&nbsp;20%&nbsp;&nbsp;&nbsp;</option>
		  <option value="30">&nbsp;&nbsp;&nbsp;30%&nbsp;&nbsp;&nbsp;</option>
		  <option value="40">&nbsp;&nbsp;&nbsp;40%&nbsp;&nbsp;&nbsp;</option>
          <option value="50">&nbsp;&nbsp;&nbsp;50%&nbsp;&nbsp;&nbsp;</option>
          <option value="60">&nbsp;&nbsp;&nbsp;60%&nbsp;&nbsp;&nbsp;</option>
          <option value="70">&nbsp;&nbsp;&nbsp;70%&nbsp;&nbsp;&nbsp;</option>
          <option value="80">&nbsp;&nbsp;&nbsp;80%&nbsp;&nbsp;&nbsp;</option>
          <option value="90">&nbsp;&nbsp;&nbsp;90%&nbsp;&nbsp;&nbsp;</option>
          <option value="100">&nbsp;&nbsp;&nbsp;100%&nbsp;&nbsp;</option>
      </select><span class="textareaRequiredMsg">Please select a value for this result range!</span></span>
    <span class="desc">Select the MINIMUM percentage range of correct answers you want the users to get to achieve this result</span></td> 
  <!--<script>QuizResultTest.slider();</script>
<div class="slider">
	<span class="amount"></span>
      
      <!-- // might help in getting value for next result starting % - LIEN
      <span>Start:
        <span id="start"></span>
    </span>
    <span>
        <span id="delta"></span>
    </span>
     //end of might-help 
    
      <div class="slider-range"></div> 
      <?php //$what = 21  ;// testing purpose,along with next line - LIEN ?>
      <input class="lowerbound" type="hidden" name="lowerbound" value="<?php //echo $what ?>" />
 </div>
 <span class="desc">Select the percentage range of correct answers you want the users to get to achieve this result.</span>-->
 <tr></tr>
 </tr>



 
  <tr>
    <th width="120" rowspan="4" valign="top" scope="row"><label>Picture</label>
      <input name="result_picture_<?php echo $result; ?>" type="hidden" id="result_picture_<?php echo $result; ?>" value="" /></th>
    <td><div id="swfupload-control-<?php echo $result; ?>" class="swfupload-control">
      <script>initUploader("result_picture_<?php echo $result; ?>")</script>
      <table border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td><input name="uploader-<?php echo $result; ?>" type="button" id="uploader-<?php echo $result; ?>" /></td>
          <td valign="middle" class="formDesc">Upload a new picture (jpg, gif or png); You can select more than 1 file!</td>
          </tr>
    </table>
<table border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td><div id="selected-image-<?php echo $result; ?>" class="selected-image"></div></td>
    <td><p id="queuestatus-<?php echo $result; ?>"></p></td>
  </tr>
</table>
      <ol id="log-<?php echo $result; ?>" class="log">
      </ol>
    </div></td>
  </tr>
  <tr>
    <td><div id="pictureChoser_<?php echo $result; ?>"><?php if(sizeof(glob("../quiz_images/".$unikey."*")) > 0){ ?><table border="0" cellspacing="0" cellpadding="3">
  <tr>
    <td><span class="formDesc">OR click on a picture below to use it as the result picture</span></td>
  </tr>
  <tr>
    <td><?php // return uploaded images
	if($unikey != ""){ foreach(glob("../quiz_images/".$unikey."*") as $filename){ ?>
		<a href="javascript:;" onClick="selectImage(<?php echo $result; ?>, '<?php echo basename($filename); ?>')"><img src="../quiz_images/imgcrop.php?w=80&h=60&f=<?php echo basename($filename); ?>" width="80" height="60" id="r<?php echo $result; ?>i<?php echo $count; ?>" class="selectImage"></a>
	<?php $count++; }} ?>
	</td>
  </tr>
</table><?php } ?></div></td>
  </tr>
</table>
</div>
<?php } ?>