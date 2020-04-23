<?php

namespace SqlProcessing;

use SqlProcessing\Steps\Step;

/**
 * Интерфейс SqlProcess
 *
 * Предназначен для запуска шагов процесса в заданном порядке.
 * Шаги можно добавлять по отдельности с помощью {@see addStep()}
 * или сразу несколько с помощью {@see addSteps()}
 *
 * Удаление шагов не предусмотрено (пока что)
 *
 * @package SqlProcessing
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
interface SqlProcess
{

    /**
     * @param Step $step
     * @return void
     */
    function addStep(Step $step);

    /**
     * Добавить все шаги
     *
     * @param Step[] $steps
     * @return void
     */
    function addSteps(array $steps);

    /**
     * Запустить SQL процесс
     *
     * @return void
     */
    function run();
}