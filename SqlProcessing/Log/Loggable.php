<?php

namespace SqlProcessing\Log;

/**
 * Класс, который использует логгер {@see Logger}
 *
 * @package SqlProcessing\Log
 *
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
interface Loggable
{
    /**
     * @param Logger
     * @return void
     */
    public function setLogger(Logger $logger);

    /**
     * @return Logger
     */
    public function getLogger();
}