<?php

///Email class used to create email objects and send email messages to users
//Version 1.0 will provide basic email functionality and sending of plain text messages
class Email {
    
    function sendMessage($to,$subject,$body){
        // Pear Mail Library
        require_once "Mail.php";

        $from = 'IPRO Proposals <iproadmin@ideashop.iit.edu>';
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
    
   function approvalMail($email, $deanName){
	$mailText = <<<EOT
Hello $deanName, 

Please be advised that you have an IPRO proposal submitted by a faculty member in your college that is awaiting your review and approval. Please login to the IPRO Proposal System using your login credentials. If you have forgotten your user name and password, please email me at jacobius@iit.edu.

URL: https://ipro.iit.edu/proposals/

Once logged in, you may review, comment and/or approve the IPRO proposals in your queue. We encourage faculty members, both regular and part-time faculty, to discuss their intent to propose an IPRO project with their appropriate department chairperson and college dean prior to submitting a proposal. We suggest that your proposal review considerations may then encompass the following, in consultation with the appropriate academic unit head:

1. Is the proposer available to serve as an IPRO instructor as part of her/is courseload?
2. Is the nature of the proposed project likely to offer students in my academic unit a challenging and meaningful experience that supports their degree pursuits?
3. Does the proposed project serve to complement, build or strengthen a faculty member's portfolio of teaching, research, publication and service to the university?
4. Does the proposed project support the overall strategic goals of the academic unit?
5. Has the faculty member had successful student outcomes associated with previous IPRO projects?
6. Are there proposals pending from other faculty members that need to be given priority for the next semester?

Based on your reflection of the above considerations, and with your approval, the IPRO proposal will be considered by the IPRO Program Office to assure that the project offers an experience that fulfills the IPRO learning goals. With our mutual consent, the proposed IPRO project will be included in our listing for the upcoming semester.

If you have any questions or concerns, please let me know.

Tom Jacobius
EOT;
               return $this->sendMessage($email, 'IPRO Proposal Awaiting Review and Approval', $mailText);           
   }
}

?>
