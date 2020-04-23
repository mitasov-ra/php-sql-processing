<?php
/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */

namespace SqlProcessing\Exceptions;


use Exception;

class SqlFileParseException extends SqlProcessingException
{
    public function __construct($message, $line, Exception $previous = null)
    {
        parent::__construct(
            "Sql file parse error on line $line. Message: $message",
            self::SQL_FILE_PARSE_EXCEPTION,
            $previous
        );
    }
}