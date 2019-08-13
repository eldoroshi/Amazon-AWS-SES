<?php

require_once '../../php/vendor/autoload.php';


use Aws\Ses\SesClient;
use Aws\Exception\AwsException;


$recipient_emails = ['eldoroshi@gmail.com', 'rakelaruco@gmail.com'];

$i = 0;

/* Its important to send only 50 emails for one api call */
$freq = 2;

$receipt_newarray = array();
foreach($recipient_emails as $receipt){
    $i++;  
    array_push($receipt_newarray, $receipt); 

   if($i % $freq  == 0){
      
   amazon_sendEmail($receipt_newarray);
   $receipt_newarray = array();
     

   }

}   


function amazon_sendEmail($receipt_newarray){

      // Create an SesClient. Change the value of the region parameter if you're 
      // using an AWS Region other than US West (Oregon). Change the value of the
      // profile parameter if you want to use a profile in your credentials file
      // other than the default.
      $SesClient = new SesClient([
          'version' => 'latest',
          'region'  => 'eu-west-1',
          'credentials' => [
              'key' => '',
              'secret' => '',
        ]
      ]);

      // Replace sender@example.com with your "From" address.
      // This address must be verified with Amazon SES.
      $sender_email = 'codeless.sol@gmail.com';
      $name = 'Codeless <codeless.sol@gmail.com>';
      // Replace these sample addresses with the addresses of your recipients. If
      // your account is still in the sandbox, these addresses must be verified.


   
      // Specify a configuration set. If you do not want to use a configuration
      // set, comment the following variable, and the
      // 'ConfigurationSetName' => $configuration_set argument below.
      //$configuration_set = 'ConfigSet';

      $subject = 'Amazon SES test (AWS SDK for PHP)';
      $plaintext_body = 'This email was sent with Amazon SES using the AWS SDK for PHP.' ;
      $html_body =  '<h1>AWS Amazon Simple Email Service Test Email</h1>'.
                    '<p>This email was sent with <a href="https://aws.amazon.com/ses/">'.
                    'Amazon SES</a> using the <a href="https://aws.amazon.com/sdk-for-php/">'.
                    'AWS SDK for PHP</a>.</p>';
      $char_set = 'UTF-8';

      try {
          $result = $SesClient->sendEmail([
              'Destination' => [
                  'ToAddresses' => $receipt_newarray,
              ],
              'ReplyToAddresses' => [$sender_email],
              'Source' => $name, 
              'Message' => [
                'Body' => [
                    'Html' => [
                        'Charset' => $char_set,
                        'Data' => $html_body,
                    ],
                    'Text' => [
                        'Charset' => $char_set,
                        'Data' => $plaintext_body,
                    ],
                ],
                'Subject' => [
                    'Charset' => $char_set,
                    'Data' => $subject,
                ],
              ],
              // If you aren't using a configuration set, comment or delete the
              // following line
              //'ConfigurationSetName' => $configuration_set,
          ]);
          $messageId = $result['MessageId'];
          echo("Email sent! Message ID: $messageId"."\n");
      } catch (AwsException $e) {
          // output error message if fails
          echo $e->getMessage();
          echo("The email was not sent. Error message: ".$e->getAwsErrorMessage()."\n");
          echo "\n";
      }
  }
   
  
