<?php

class IpService
{
	private FileStore $fileStore;

	public function __construct($path = null)
	{
		if ($path) {
			$this->fileStore = new FileStore($path);
		} else {
			$base = realpath(__DIR__ . '/..');
			$this->fileStore = new FileStore($base . DIRECTORY_SEPARATOR . 'logs' . DIRECTORY_SEPARATOR . 'access.log');
		}
	}

	public function appendLog($ip, $time, $quantity): bool
    {
		$line = implode('|', array($ip, $time, $quantity)) . PHP_EOL;
		return $this->fileStore->append($line);
	}
}


