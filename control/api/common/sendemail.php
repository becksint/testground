<?php

function sendmail($post){
  $content =  formatpost($post); //formats post
  require '../api/phpmailer/Exception.php';
  require '../api/phpmailer/PHPMailer.php';
  require '../api/phpmailer/SMTP.php';
  $mail = new PHPMailer\PHPMailer\PHPMailer();
  $json = json_read('settings');  //reads the json settings file
  if(isset($json['mail'])){$setup = $json['mail'];}
  foreach ($setup as $key => $value) {
    switch (TRUE) {
      case $key == 'smtp' : $mail->isSMTP();   break; 
      case $key == 'from' : if(isset($post['email'])){ $mail->setFrom($post['email']); } else{ $mail->setFrom($value['email'], $value['name']);} break;
      case $key == 'reply': $mail->addReplyTo($value['email'], $value['name']); break; 
      case $key == 'to'   : $mail->addAddress($value['email'], $value['name']); break; 
      case $key == 'cc' : if(strlen($value)>3) { $mail->addCC($value); }  break;
      case $key == 'bcc'  : if(strlen($value)>3) { $mail->addBCC($value); }  break; 
      case $key == 'html' : $mail->isHTML(true);  break;  // Set email format to HTML 
      case $key == 'subject' : if(isset($post['subject'])){ $mail->Subject =  $post['subject']; }  else { $mail->Subject = $value; } break; 
      case !empty($value) : $mail->{$key} = $value; break;
      default: break;
    }
  }  
  $mail->Body    = $content;  $mail->AltBody = $content;
  if(!$mail->send()) {  echo $json['feedback']['success']; /*.$mail->ErrorInfo*/   } 
  else {   echo $json['feedback']['failure']; }
}


function formatpost($post){
  $content = '<table>';
  foreach ($post as $key2 => $value2) {    
                                          $content .= '<tr><td style="background:#333333; color:#ffffff; font-size:20px;"><b>'.$key2.'</b></td></tr><tr><td>';              
                                           if(!is_array($value2)){ $content .= $value2; }
                                           else {     $content .= '<table>';
                                                      foreach ($value2 as $key3 => $value3) {
                                                       $content .=   '<tr><td style="font-size:15px;"><b>'.$key3.'</b></td><td style="font-size:15px;"><b>'.$value3.'</b></td></tr>';
                                                      }
                                                      $content .= '</table>';
                                                  }
                                           $content .=  '</td></tr>'; }
  $content .= '</table>';
  return $content; 

}


/*
SYNTAX 
border="0" cellpadding="0" cellspacing="0"
<table>
#FFFFFF
padding
background-color
color
<a href="" target="_blank">Take action now</a>


font georgia , arial, verdana
font:#0000000 12px Arial, Helvetica, sans-serif;”


<table cellspacing="0" cellpadding="0" border="0" max-width="800px">
  <tr>
    <td width="150"></td>
    <td width="350"></td>
  </tr>
</table>

<p style='line-height:1.5em;margin:0px 0px 10px 0px;'>Lorem</p>





<img src="https://www.smashingmagazine.com/wp-content/uploads/2016/11/logo.png" height="100" width="600" alt="Company Logo" style="max-width: 100%;">


<!--[if (gte mso 9)|(IE)]>

<table align="left" border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
    <td align="left" valign="top" width="50%">
    <![endif]-->
      <div class="span-3" style="display: inline-block; Margin-bottom: 40px; vertical-align: top; width: 100%; max-width: 278px;">...</div>
    <!--[if (gte mso 9)|(IE)]>
    </td>
    <td align="left" valign="top" width="50%">
    <![endif]-->
      <div class="span-3" style="display: inline-block; Margin-bottom: 40px; vertical-align: top; width: 100%; max-width: 278px;">...</div>
    <!--[if (gte mso 9)|(IE)]>
    </td>
  </tr>
</table>

<![endif]-->


*/


?>