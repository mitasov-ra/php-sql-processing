<?php

namespace SqlProcessing\Runners;

/**
 * Интерфейс, используемый другими для запуска SQL скриптов
 *
 * Нужен для того, чтобы отвязать SQL процессинг от конкретной
 * реализации подключения к БД.
 *
 * Если нужно использовать PDO - {@see PDOSqlRunner}.
 *
 * Если используется фреймворк - нужно просто написать реализацию,
 * используя его.
 *
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
interface SqlRunner
{
    public function execute($sql, $params);

    public function fetchAllAssoc($sql, $params);

    public function fetchAllBoth($sql, $params);

    public function fetchRowAssoc($sql, $params);

    public function fetchRowBoth($sql, $params);
}