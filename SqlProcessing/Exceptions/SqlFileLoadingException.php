<?php

namespace SqlProcessing\Exceptions;

use Exception;

/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
class SqlFileLoadingException extends SqlProcessingException
{
    public function __construct(
        $message,
        Exception $previous = null
    ) {
        parent::__construct($message, self::SQL_FILE_EXCEPTION, $previous);
    }
}