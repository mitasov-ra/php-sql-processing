<?php

namespace SqlProcessing\Steps;

use SqlProcessing\Runners\SqlRunner;

/**
 * Шаг запуска SQL запроса.
 *
 * Позволяет запустить SQL запрос из строки, используя переданные параметры.
 *
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
class ExecuteSqlStatementStep implements SqlStep
{
    private $_sqlScript;

    private $_params;

    /** @var SqlRunner */
    private $_sqlRunner;

    public function run()
    {
        $this->_sqlRunner->execute($this->_sqlScript, $this->_params);
    }

    public function setSqlRunner(SqlRunner $runner)
    {
        $this->_sqlRunner = $runner;
    }

    /**
     * @return mixed
     */
    public function getSqlScript()
    {
        return $this->_sqlScript;
    }

    /**
     * @param mixed $sqlScript
     */
    public function setSqlScript($sqlScript)
    {
        $this->_sqlScript = $sqlScript;
    }

    /**
     * @return mixed
     */
    public function getParams()
    {
        return $this->_params;
    }

    /**
     * @param mixed $params
     */
    public function setParams($params)
    {
        $this->_params = $params;
    }

    /**
     * @return SqlRunner
     */
    public function getSqlRunner()
    {
        return $this->_sqlRunner;
    }
}