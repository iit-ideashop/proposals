<?php

///Email class used to create email objects and send email messages to users
//Version 1.0 will provide basic email functionality and sending of plain text messages
class Email {
    
    function sendMessage($to,$subject,$body){
        // Pear Mail Library
        require_once "Mail.php";

        $from = 'IPRO Proposals <ipro@iit.edu>';
        $to = '<'.  Database::sterilizeStr($to).'>';
        $subject = Database::sterilizeStr($subject);
        $body = Database::sterilizeStr($body);

        $headers = array(
            'From' => $from,
            'To' => $to,
            'Subject' => $subject
        );

        $smtp = Mail::factory('smtp', array(
            'host' => '127.0.0.1',
            'port' => '25',
        #    'auth' => true,
        #   'username' => 'ipro@iit.edu',
        #    'password' => 'redacted'
        ));

        $mail = $smtp->send($to, $headers, $body);
    }
    
    
}

?>
