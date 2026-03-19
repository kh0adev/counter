<?php

require_once __DIR__ . '/../config/FileStore.php';
require_once __DIR__ . '/../config/IpService.php';
require_once __DIR__ . '/Counter.php';

class CounterService
{
    private const DateTimeFormat = 'Y-m-d H:i:s';
    private Database $db;
	private FileStore $fileStore;
	private IpService $ipService;

	public function __construct($db)
	{
		$this->db = $db;
		$this->fileStore = new FileStore();
		$this->ipService = new IpService();
	}

	private function readFile(): ?array
    {
		return $this->fileStore->read();
	}

	private function writeFile($data): bool
    {
		return $this->fileStore->write($data);
	}

	private function dbFetch(): ?Counter
    {
		if ($this->db->conn) {
			try {
				$stmt = $this->db->conn->prepare('SELECT * FROM counter WHERE Id = 1');
				$stmt->execute();
				if ($stmt->rowCount() > 0) {
					$row = $stmt->fetch(PDO::FETCH_ASSOC);
                    $c = new Counter();
                    $c->id = $row['Id'];
                    $c->quantity = $row['Quantity'];
                    $c->updateDate = DateTime::createFromFormat(self::DateTimeFormat, $row['UpdateDate']);
                    return $c;
				}
			} catch (Exception $e) {
                echo "DB Error: " . $e->getMessage();
				return null;
			}
		}
		return null;
	}

	public function fetch(): ?Counter
	{
		$data = $this->dbFetch();
		if ($data !== null) {
			return $data;
		}

		$file = $this->readFile();
		if ($file !== null) {
			$c = new Counter();
			$c->id = $file['id'] ?? 1;
			$c->quantity = isset($file['quantity']) ? (int)$file['quantity'] : 0;
			$c->updateDate = DateTime::createFromFormat(self::DateTimeFormat,$file['updateDate'] ?? date(self::DateTimeFormat));
			return $c;
		}
        return null;
	}

	public function increment($ip = null)
	{
        if ($ip && $this->ipService->isSpam($ip)) {
            return;
        }

		$time = date(self::DateTimeFormat);

		if ($this->db->conn) {
			try {
				$this->db->conn->exec("UPDATE counter SET Quantity = Quantity + 1, UpdateDate = NOW() WHERE Id = 1");
			} catch (Exception $e) {
                echo "DB Error: " . $e->getMessage();
			}
		}

		// update file
		$fileData = $this->readFile();
		if ($fileData === null) {
            $fileData = array('id' => 1, 'quantity' => 0, 'updateDate' => $time);
        }
		$fileData['quantity'] = (int)$fileData['quantity'] + 1;
		$this->writeFile($fileData);

		$this->ipService->appendLog($ip ?: 'unknown', $time, $fileData['quantity']);
	}

	public function reset(): array
    {
		$time = date(self::DateTimeFormat);
		$okDb = false;
		if ($this->db->conn) {
			try {
				$this->db->conn->exec("UPDATE counter SET Quantity = 0, UpdateDate = NOW() WHERE Id = 1");
			} catch (Exception $e) {
                echo "DB Error: " . $e->getMessage();
			}
		}

		$data = array('id' => 1, 'quantity' => 0, 'updateDate' => $time);
        $this->writeFile($data);
		$this->ipService->appendLog('reset', $time, 0);

		return array('quantity' => 0, 'updateDate' => $time);
	}
}


