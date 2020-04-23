<?php
/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */

namespace SqlProcessing\Exceptions;


use Exception;

class SqlRunnerException extends SqlProcessingException
{
    public function __construct(
        $message,
        Exception $previous = null
    ) {
        parent::__construct($message, self::SQL_RUNNER_EXCEPTION, $previous);
    }
}