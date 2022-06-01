<?php

namespace LoggerHelper;

use PDO;

abstract class LogHelper
{
    /**
     * @var PDO $db
     */
    private $db;
    /**
     * @var int $version
     */
    private $version;

    public function __construct($db_name = "log.sqlite", $version = 1)
    {
        $this->db = new PDO("sqlite:" . LOG_PATH . $db_name, null, null, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        $this->version = $version;
        $this->init();
    }

    /**
     * @return PDO
     */
    protected function getDatabase(): PDO {
        return $this->db;
    }

    private function init() {
        $current_version = intval($this->db->query("PRAGMA user_version")->fetch(PDO::FETCH_COLUMN));
        if ($current_version === 0) {
            $this->onCreate($this->db);
            $this->db->exec("PRAGMA user_version = 1");
        } else if ($current_version < $this->version) {
            $this->onUpgrade($current_version, $this->version, $this->db);
            $this->db->exec("PRAGMA user_version = {$this->version}");
        }
    }

    /**
     * @param PDO $db
     */
    abstract protected function onCreate(PDO $db);

    abstract protected function onUpgrade($old_version, $new_version, $db);
}