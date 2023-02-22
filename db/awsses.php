<?php

// If necessary, modify the path in the require statement below to refer to the
// location of your Composer autoload.php file.
require '/Users/mikhailleonov/vendor/autoload.php';

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

class awsMail
{

	// Create an SesClient. Change the value of the region parameter if you're
// using an AWS Region other than US West (Oregon). Change the value of the
// profile parameter if you want to use a profile in your credentials file
// other than the default.
	private $SesClient;
	//  = new SesClient([
	// 	'profile' => 'default',
	// 	'version' => 'latest',
	// 	'region' => 'us-east-1'
	// ]);

	function __construct()
    {
		$this->SesClient = new SesClient([
			// 'profile' => 'default',
			'version' => 'latest',
			'region' => 'us-east-1',
			'credentials' => [
				'key' => 'AKIASIUJXHEWQ5XZ3MWC',
				'secret' => '3+a9quqp9evnltkXYtJowrHOUeIdY+/0N7j1HCvQ',
			]
		]);

    }

	// Replace sender@example.com with your "From" address.
// This address must be verified with Amazon SES.
	private $sender_email = 'robot@rfbuild.ru';

	// Replace these sample addresses with the addresses of your recipients. If
// your account is still in the sandbox, these addresses must be verified.
	private $recipient_emails;

	// Specify a configuration set. If you do not want to use a configuration
// set, comment the following variable, and the
// 'ConfigurationSetName' => $configuration_set argument below.
// $configuration_set = 'ConfigSet';

	public function SendEmail($recipient, $msg)
	{
		$recipient_emails[] = $recipient;
		$subject = 'Amazon SES test (AWS SDK for PHP)';
		$plaintext_body = 'This email was sent with Amazon SES using the AWS SDK for PHP.';
		$html_body = '<h1>AWS Amazon Simple Email Service Test Email</h1>' .
			'<p>This email was sent with <a href="https://aws.amazon.com/ses/">' .
			'Amazon SES</a> using the <a href="https://aws.amazon.com/sdk-for-php/">' .
			'AWS SDK for PHP</a>.</p>';
		$html_body .= '<p>'.$msg.'<p>';
		$char_set = 'UTF-8';

		try {
			$result = $this->SesClient->sendEmail([
				'Destination' => [
					'ToAddresses' => $recipient_emails,
				],
				'ReplyToAddresses' => [$this->sender_email],
				'Source' => $this->sender_email,
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
				// 'ConfigurationSetName' => $configuration_set,
			]);
			$messageId = $result['MessageId'];
			echo ("Email sent! Message ID: $messageId" . "\n");
		} catch (AwsException $e) {
			// output error message if fails
			echo $e->getMessage();
			echo ("The email was not sent. Error message: " . $e->getAwsErrorMessage() . "\n");
			echo "\n";
		}
	}
}
