<?php

class FileStore
{
    private $dataFile;

    public function __construct($path = null)
    {
        if ($path) {
            $this->dataFile = $path;
        } else {
            $base = realpath(__DIR__ . '/..');
            $this->dataFile = $base . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'counter.json';
        }
    }

    public function read(): ?array
    {
        if (!file_exists($this->dataFile)) return null;
        $content = file_get_contents($this->dataFile);
        if ($content === false) return null;
        $data = json_decode($content, true);
        return is_array($data) ? $data : null;
    }

    public function write(array $data): bool
    {
        $dir = dirname($this->dataFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        return file_put_contents($this->dataFile, $json) !== false;
    }

    public function append(string $line): bool
    {
        $dir = dirname($this->dataFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        return file_put_contents($this->dataFile, $line, FILE_APPEND | LOCK_EX) !== false;
    }
}

