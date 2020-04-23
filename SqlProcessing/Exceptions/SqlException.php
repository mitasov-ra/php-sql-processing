<?php

namespace SqlProcessing\Exceptions;

use Exception;

/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
class SqlException extends SqlProcessingException
{
    public function __construct(
        $sql,
        Exception $previous = null
    ) {
        $message = "Возникло исключение при попытке выполнить запрос: $sql";

        if ($previous !== null) {
            $message .= " \nИсключение: $previous->message";
        }
        parent::__construct(
            $message,
            self::SQL_EXCEPTION,
            $previous
        );
    }
}