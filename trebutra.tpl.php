<?php

// checks if form was submitted
$trello_key          = variable_get('trebutra_key');
$trello_api_endpoint = variable_get('trebutra_apiend');
$trello_list_id      = variable_get('trebutra_listid');
$trello_member_token = variable_get('trebutra_token');

if(isset($_POST['title'])) {

	$v1 = $_POST['title'];
	$v2 = $_POST['description'];
	$v3 = $_POST['email'];
	$v4 = $_POST['severity'];
	$name = $v4." - ".$v1;
	$description = "**Severity**: ".$v4."\n\n
	**Description**: ".$v2."\n\n
	**Submitted by**: ".$v3;

	$ch = curl_init("$trello_api_endpoint/cards");
	curl_setopt_array($ch, array(
		CURLOPT_SSL_VERIFYPEER => false, // Probably won't work otherwise
		CURLOPT_RETURNTRANSFER => true, // So we can get the URL of the newly-created card
		CURLOPT_POST           => true,
		CURLOPT_POSTFIELDS => http_build_query(array( // if you use an array without being wrapped in http_build_query, the Trello API server won't recognize your POST variables
			'key'    => $trello_key,
			'token'  => $trello_member_token,
			'idList' => $trello_list_id,
			'name'   => $name,
			'desc'   => $description
		)),
	));
	$result = curl_exec($ch);
	$trello_card = json_decode($result);
	$trello_card_url = $trello_card->url;

	$msgcat = "The following bug report has been added to Trello - Collabco Development:\n\n".$trello_card_url."\n\n".$name."\n\n".$description;

	$enableemail = variable_get('trebutra_key');
	$reportemail = variable_get('trebutra_email');
	if ($enableemail == "checked") {
		$to      = $reportemail;
		$subject = 'New bug report for Epic Collaboration';
		$message = $msgcat;
		$headers = 'From: '.$reportemail. "\r\n" .
		    'Reply-To: '.$reportemail. "\r\n" .
		    'X-Mailer: PHP/' . phpversion();
		mail($to, $subject, $message, $headers);
	}
	echo "Thank you for making a submission. If necessary, we will contact you via email for more information.<br><br>";
}

?>

<script type="text/javascript">
function validateForm(){
	var formName=document.fileabugform.subject.value;
	var eMail=document.fileabugform.email.value;
	if (formName==null || formName==""){
		alert('Please Specify Problem Title');
		return false;
	}
	if(eMail==null || eMail==""){
		alert('Please specify a valid Email Id');
		return false;}var atpos=eMail.indexOf("@");
		var dotpos=eMail.lastIndexOf(".");
		if (atpos<1 || dotpos<atpos+2 || dotpos+2>=eMail.length)  {  
			alert("Please specify a valid Email Id");  
			return false;
			  }
			  var dueDate = document.getElementById("dueDate").value;
			  if(dueDate!="null" || dueDate!=""){
			  	if (dueDate.match(/^(?:(0[1-9]|1[012])[\- \/.](0[1-9]|[12][0-9]|3[01])[\- \/.](19|20)[0-9]{2})$/)){
			  		return true;
			  	}else{
			  		alert("Please enter valid date");
			  		return false;
			  	}
			  }
			}
</script>
<div style="display:table; width:730px; margin:0px; font-family: 'Lucida Grande',Arial,Helvetica,sans-serif,Tahoma; border-radius:5px; -moz-border-radius:5px; -webkit-border-radius:5px; padding:30px; border:1px solid #BBB; box-shadow:0px 0px 3px #aaa; -moz-box-shadow:0px 0px 3px #aaa; -webkit-box-shadow:0px 0px 3px #aaa; background-color:#fff;">
<form action="submit-bug" method="post" name="fileabugform" onsubmit="return validateForm()">
<input name="id" type="hidden" value="ec" /><input name="fId" type="hidden" value="a7cadee0e7267bf09cac9ae9d926397623ca193a53051b7d" />
<div style="width:100%; font: normal 12px 'Lucida Grande',Arial,Helvetica,sans-serif,Tahoma; color:#999;">
<h2 style="color: #000; margin:3px 0;  font-size: 21px; font-weight: normal; height: 23px; padding-left: 3px; vertical-align:middle;">Submit Bug</h2>

<div>
<h3 style="color:#555; margin:20px 0px; font-size:12px; font-weight:normal;padding:5px 0px; text-transform:uppercase; float:left; border-bottom:3px double #e0e0e0;">Bug/Issue Report</h3>

<table border="0" cellpadding="5" cellspacing="0" style="clear:both;font: normal 12px 'Lucida Grande',Arial,Helvetica,sans-serif,Tahoma; color:#999;" width="100%">
	<tbody>
		<tr>
			<td align="right" style="text-align:right; vertical-align:top;" width="150px">Problem Title:</td>
			<td align="left" valign="top" width="580px">
				<input id="title" name="title" style="width:85%; border:1px solid #d0d0d0; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; padding:5px; outline:none;" type="text" value="" />
			</td>
		</tr>
		<tr>
			<td align="right" style="text-align:right; vertical-align:top; padding-top:8px;">Problem Description:</td>
			<td style="padding-top:8px;" valign="top">
				<textarea id="description" name="description" rows="4" style="width:85%; overflow-y: auto; border:1px solid #d0d0d0; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px; padding:5px; resize:vertical; outline:none;"></textarea>
			</td>
		</tr>
	</tbody>
</table>

<h3 style="color:#555; margin:20px 0px; font-size:12px; font-weight:normal;padding:5px 0px; text-transform:uppercase; float:left; border-bottom:3px double #e0e0e0;">Other information</h3>

<div style="display:block; width:100%; font: normal 12px 'Lucida Grande',Arial,Helvetica,sans-serif,Tahoma; color:#999; ">
<div style="float:left; width:30px;">&nbsp;</div>

<div style="float:right; width:700px; display:block; ">
<div style="float:left; width:50%;padding:5px 0 ; height:28px;">
<div style="float:left; width:33% ;font-weight: normal; text-align:right; padding-top:3px;">Email Address:</div>

<div style="float:left; padding-left:5px; width:65%">
<input name="email" style="width:65%; border-color: #d0d0d0; border-style: solid; border-width: 1px; color: #3A3A3A; font-size: 12px; font-weight: normal; border-radius:3px; padding:3px; outline:none;" type="text" /></div>
</div>

<div style="float:left; width:50%; padding:5px 0; height:28px;">
<div style="float:left; width:33% ;font-weight: normal; padding-top:3px; text-align:right;">Severity:</div>

<div style="float:left; padding-left:5px;  width:65%">
<select name="severity" style=" width:65%; border-color: #d0d0d0; border-style: solid; border-width: 1px; color: #3A3A3A; font-size: 12px; font-weight: normal; border-radius:3px; padding:3px; outline:none;"><option value="1">Show stopper</option><option value="2">Critical</option><option value="3">Major</option><option value="4">Minor</option></select></div>
</div>

<div style="clear:both;">&nbsp;</div>
</div>

<div style="clear:both;">&nbsp;</div>
</div>

<div style="float:left; text-align:center; background-color:#FFF;  width:100%; padding:30px 0px 0px 0px; "><input style="background-color:#3079ED;border:1px solid #246FE5; color:#FFF;cursor:pointer;padding:2px 12px;text-align:center; font:12px 'Lucida Grande',Tahoma,Arial,Helvetica,sans-serif; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px;" title="Save" type="submit" value="Save " /> <input style="background:#F1F1F1;border:1px solid #d1d1d1; color:#000;cursor:pointer;font:12px 'Lucida Grande',Tahoma,Arial,Helvetica,sans-serif; border-radius:3px; -moz-border-radius:3px; -webkit-border-radius:3px;padding:2px 12px;text-align:center;" title="Cancel" type="reset" value="Cancel" /></div>
</div>
</div>
</form>
</div>
