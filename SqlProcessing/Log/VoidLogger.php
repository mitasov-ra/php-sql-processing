<?php
/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */

namespace SqlProcessing\Log;

/**
 * Логгер пустышка, который не делает ничего
 *
 * Используется для того, чтобы вызовы логгера из классов {@see Loggable}
 * не приводили к ошибке. Используется всеми {@see Loggable} по умолчанию
 * сразу после создания объекта.
 *
 * @package SqlProcessing\Log
 *
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
class VoidLogger implements Logger
{
    public function info($message, $params = array())
    {
    }

    public function warn($message, $params = array())
    {
    }

    public function error($message, $params = array())
    {
    }

    public function debug($message, $params = array())
    {
    }
}