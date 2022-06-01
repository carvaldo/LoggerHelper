<?php

namespace LoggerHelper;

use PDO;

class AttachLogger extends LogHelper
{
    const DB_VERSION = 1;
    /**
     * @var PDO
     */
    private $db;

    public function __construct()
    {;
        parent::__construct("attach_log_" . date("Y") . ".sqlite", self::DB_VERSION);
        $this->db = parent::getDatabase();
    }

    protected function onCreate(PDO $db)
    {
        $ddl = "CREATE TABLE _create(" .
            "id INTEGER PRIMARY KEY  AUTOINCREMENT, " .
            "time TEXT default CURRENT_TIMESTAMP, " .
            "usuario_ref INTEGER, " .
            "usuario_nome TEXT, " .
            "arquivo_ref INTEGER, " .
            "arquivo TEXT, " .
            "conteudo TEXT, " .
            "ip TEXT" .
            ")";
        $db->exec($ddl);
    }

    protected function onUpgrade($old_version, $new_version, $db)
    {
    }
}