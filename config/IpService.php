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

    public function isSpam($ip, $interval = 5)
    {
        $lines = $this->fileStore->getIpLog();
        if ($lines === null) {
            return false;
        } else {
            for ($i = count($lines) - 1; $i >= 0; $i--) {
                $parts = explode('|', $lines[$i]);
                if (count($parts) < 2) continue;
                if ($parts[0] === $ip) {
                    $lastTime = strtotime($parts[1]);
                    if ($lastTime === false) return false;
                    return (time() - $lastTime) < $interval;
                }
            }
            return false;
        }
    }
}


