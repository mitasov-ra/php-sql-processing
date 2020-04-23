<?php
/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */

namespace SqlProcessing\Exceptions;


use Exception;

class StepConfigurationException extends SqlProcessingException
{
    public function __construct($message, Exception $previous = null)
    {
        parent::__construct($message, self::STEP_CONFIG_EXCEPTION, $previous);
    }
}