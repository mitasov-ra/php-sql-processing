<?php

namespace SqlProcessing\Runners;

use PDO;
use PDOException;
use SqlProcessing\Exceptions\SqlException;
use SqlProcessing\Log\Logger;
use SqlProcessing\Log\VoidLogger;
use SqlProcessing\Utils\LogUtils;

/**
 * Sql раннер, использующий PDO
 *
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
class PDOSqlRunner implements SqlRunner
{
    /** @var PDO */
    private $_pdoInstance;

    /** @var Logger */
    private $log;

    public function __construct()
    {
        $this->log = new VoidLogger();
    }

    public function execute($sql, $params)
    {
        try {
            $s = $this->_pdoInstance->prepare($sql);
            LogUtils::logSqlRun($this->log, $sql, $params);
            if (!$s->execute()) {
                throw new SqlException($sql);
            }
            $this->log->debug("SQL затронул {0} записей", array($s->rowCount()));
            return $s->rowCount();
        } catch (PDOException $e) {
            throw new SqlException($sql, $e);
        }
    }

    public function setPdoInstance($pdoInstance)
    {
        $this->_pdoInstance = $pdoInstance;
    }

    public function fetchAllAssoc($sql, $params)
    {
        try {
            $s = $this->_pdoInstance->prepare($sql);

            LogUtils::logSqlRun($this->log, $sql, $params);

            if (!$s->execute()) {
                throw new SqlException($sql);
            }
            $this->log->debug("SQL вернул {0} записей", array($s->rowCount()));
            return $s->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new SqlException($sql, $e);
        }
    }

    public function fetchRowAssoc($sql, $params)
    {
        try {
            $s = $this->_pdoInstance->prepare($sql);
            LogUtils::logSqlRun($this->log, $sql, $params);
            if (!$s->execute()) {
                throw new SqlException($sql);
            }
            $this->log->debug("SQL вернул {0} записей", array($s->rowCount()));
            return $s->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new SqlException($sql, $e);
        }
    }

    public function setLogger(Logger $logger)
    {
        $this->log = $logger;
    }

    public function fetchRowBoth($sql, $params)
    {
        try {
            $s = $this->_pdoInstance->prepare($sql);
            LogUtils::logSqlRun($this->log, $sql, $params);
            if (!$s->execute()) {
                throw new SqlException($sql);
            }
            $this->log->debug("SQL вернул {0} записей", array($s->rowCount()));
            return $s->fetch(PDO::FETCH_BOTH);
        } catch (PDOException $e) {
            throw new SqlException($sql, $e);
        }
    }

    public function fetchAllBoth($sql, $params)
    {
        try {
            $s = $this->_pdoInstance->prepare($sql);
            LogUtils::logSqlRun($this->log, $sql, $params);
            if (!$s->execute()) {
                throw new SqlException($sql);
            }
            $this->log->debug("SQL вернул {0} записей", array($s->rowCount()));
            return $s->fetchAll(PDO::FETCH_BOTH);
        } catch (PDOException $e) {
            throw new SqlException($sql, $e);
        }
    }
}