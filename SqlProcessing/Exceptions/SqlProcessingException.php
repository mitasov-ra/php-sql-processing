<?php

namespace SqlProcessing\Exceptions;

use Exception;

/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
abstract class SqlProcessingException extends Exception
{
    const SQL_PROCESSING_EXCEPTION = 2000;
    const SQL_EXCEPTION = 2001;
    const SQL_FILE_EXCEPTION = 2002;
    const SQL_RUNNER_EXCEPTION = 2003;
    const STEP_CONFIG_EXCEPTION = 2004;
    const SQL_FILE_PARSE_EXCEPTION = 2005;

    public function __construct(
        $message,
        $code = self::SQL_PROCESSING_EXCEPTION,
        Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}