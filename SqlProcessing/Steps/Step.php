<?php

namespace SqlProcessing\Steps;

/**
 * Шаг процесса
 *
 * Может делать вообще что угодно. {@see SqlProcess} его запускает в нужный момент
 */
interface Step
{
    public function run();
}