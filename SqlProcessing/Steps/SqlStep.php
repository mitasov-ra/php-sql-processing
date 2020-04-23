<?php
/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */

namespace SqlProcessing\Steps;

use SqlProcessing\Runners\SqlRunner;

/**
 * Шаг, работающий с SQL.
 *
 * Требует подключения {@see SqlRunner SQL раннера}
 *
 * @package SqlProcessing\Steps
 */
interface SqlStep extends Step
{
    /**
     * @param SqlRunner $runner
     * @return void
     */
    public function setSqlRunner(SqlRunner $runner);

    /**
     * @return SqlRunner
     */
    public function getSqlRunner();
}