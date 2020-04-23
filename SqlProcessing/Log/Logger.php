<?php

namespace SqlProcessing\Log;

/**
 * Логгер
 *
 * Расчитан на сообщения с плейсхолдерами формата `{0}, {1}` для
 * индексированных массивов и `{key1}, {key2}` для ассоциативных.
 *
 * По сути, в `{}` всегда указывается ключ к нужному элементу в массиве
 * параметров.
 *
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
interface Logger
{
    public function info($message, $params = array());
    public function warn($message, $params = array());
    public function error($message, $params = array());
    public function debug($message, $params = array());
}