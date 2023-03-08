<?php

require $_SESSION['config']['vendor_dir'] . '/vendor/autoload.php';

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;

class AwsSES
{
	// Create an SesClient.
	private $SesClient;

	public function __construct()
	{
		$credentials = parse_ini_file($_SESSION['config']['vendor_dir'] . "/aws.ini", true)['default'];

		$this->SesClient = new SesClient([
			'version' => 'latest',
			'region' => 'us-east-1',
			'profile' => 'default',
			'version' => '2012-08-10'
//			'credentials' => $credentials
		]);
		$this->sender_email = 'robot@rfbuild.ru';
	}

	private $sender_email;

	public function SendEmail($recipient, $msg)
	{
		$recipient_emails[] = $recipient;
		$subject = "noreply";
		$plaintext_body = "This email was sent with Amazon SES using the AWS SDK for PHP.";
		$html_body = $msg;
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
			]);

			return true;
		} catch (AwsException $e) {
			return false;
		}
	}
}

?>
