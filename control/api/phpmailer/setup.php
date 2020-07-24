<?php
// https://github.com/PHPMailer/PHPMailer SOURCE
// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Load Composer's autoloader
//require 'vendor/autoload.php';

require 'Exception.php';
require 'PHPMailer.php';
require 'SMTP.php';


function sendmail($smtp, $from, $to, $attachments, $content, $result, $msg){

/*
$smtp=array('use'=>1 or 0, 'host'=>'server', 'port'=>'587', 'user'=>'email', 'password'=>'secret' );
$emails  = array(
                'from'=>array('email'=>'from@test.com', 'name'=>'SENDER' ) 
                ,'to' =>array(
                                 array('email'=>'from@test.com', 'name'=>'SENDER' )
                                ,array('email'=>'from@test.com', 'name'=>'SENDER' ) 
                                ,array('email'=>'from@test.com', 'name'=>'SENDER' )
                            )
                ,'reply'=>array('email'=>'from@test.com', 'name'=>'SENDER' )
                ,'cc' =>array(
                                 array('email'=>'from@test.com', 'name'=>'SENDER' )
                                ,array('email'=>'from@test.com', 'name'=>'SENDER' ) 
                            ) 
                ,'bcc' =>array(
                                 array('email'=>'from@test.com', 'name'=>'SENDER' )
                                ,array('email'=>'from@test.com', 'name'=>'SENDER' ) 
                            )
              );
$attachements = array(
                        array('file'=>'/var/tmp/file.tar.gz', 'name'=>'ZIPPED FILE' )
                        ,array('file'=>'/tmp/image.jpg', 'name'=>'PICTURE NAME' )
                    );

*/



// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);

try {

    if($smtp['use'] === 1){
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = $smtp['host'];                          // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = $smtp['user'];                          // SMTP username
        $mail->Password   = $smtp['password'];                      // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = $smtp['port'];                          // TCP port to connect to
    }


    //Recipients
    $mail->setFrom($from['email'], $from['name']);
    
    foreach ($to as $key => $receiver) {
        if($key === 0){ $mail->addAddress($receiver['email'], $receiver['name']);     // Add a recipient
                        //$mail->addAddress('ellen@example.com');               // Name is optional
                       }
        else{
                //$mail->addCC('cc@example.com');
                $mail->addBCC($receiver['email'], $receiver['name']);        
            }    
    }
    $mail->addReplyTo($from['reply']['email'], $from['reply']['name']);
    

    // Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

    // Content
    $mail->isHTML(true);                                  // Set email format to HTML
    $mail->Subject = $content['subject'];
    $mail->Body    = $content['msg'];
    $mail->AltBody = $content['msgplain'];

    $mail->send();
    $result = array('success'=>1, 'msg'=>$msg['win']);
} catch (Exception $e) {
    $result = array('success' =>0, 'msg'=>$msg['fail']);
}

    return $result;
}

?>  
