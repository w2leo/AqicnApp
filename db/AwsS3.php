<?php

require $_SESSION['config']['vendor_dir'] . '/vendor/autoload.php';

use Aws\S3\Transfer;


class AwsS3
{
	private $client;
	private $source = 's3://bucket';
	// Where the files will be source from
	// private $source = '/path/to/source/files';

	// Where the files will be transferred to
	private $dest = 's3://bucket';
	protected function __construct($fileName)
	{
		$client = new \Aws\S3\S3Client([
			'region' => 'us-east-1',
			'version' => 'latest',
		]);
	}
}
?>
