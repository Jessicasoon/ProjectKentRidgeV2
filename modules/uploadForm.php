<!-- form for uploading file
When user click submit button, go to another page. Here the specified page is import.php
-->
<?php session_start();
$_SESSION['file'];
?>
<div id = "uploadingForm">
<form enctype="multipart/form-data" action="uploadQuiz.php" method="post">
  <input type="hidden" name="MAX_FILE_SIZE" value="2000000" />
  <table width="632">
  <tr>
  <td width="109">Quiz Type:</td>
  <td><select name="type">
  		<option value="">Choose type of Quiz</option>
        <option value="test">Test</option>
        <option value="personality">Personality</option>
      </select>
      </td>
  </tr>
  <tr>
  <td width="109">File name:</td>
  <td width="218"><input type="file" name="file" /></td>
  <td width="289"><input type="submit" value="Upload"/></td>
  </tr>
  </table>
  </form>
  </div>