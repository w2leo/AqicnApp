<?php

// If necessary, modify the path in the require statement below to refer to the
// location of your Composer autoload.php file.
if (strripos(php_uname(), 'MacBook'))
	$v_dir = '/Users/mikhailleonov';
else
	$v_dir = '.';

require $v_dir . '/vendor/autoload.php';

use Aws\Ses\SesClient;
use Aws\Exception\AwsException;
use Aws\DynamoDb\DynamoDbClient;



class testAws
{

	public $SesConnector;
	public $DbConnector;

	public function ConnectSES()
	{
		$this->SesConnector = new SesClient(
			[
				'version' => 'latest',
				'region' => 'us-east-1'
			]
		);
	}

	public function ConnectDb()
	{
		$connectionData = array(
			'region' => 'us-east-1',
			'version' => 'latest'
		);

		$this->DbConnector = DynamoDbClient::factory($connectionData);
	}


	private $sender_email = 'robot@rfbuild.ru';
	private $recipient_emails;
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
			$result = $this->SesConnector->sendEmail([
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


	public function GetUserData()
	{
		$this->ConnectDb();
		$result = $this->DbConnector->getItem(
			array(
				'ConsistentRead' => true,
				'TableName' => 'UsersData',
				'Key' => array(
					'Login' => ['S' => 'w1']
				)
			)
		);
		return $result;
	}


}

?>
