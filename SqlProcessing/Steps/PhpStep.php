<?php

namespace SqlProcessing\Steps;

/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
class PhpStep implements Step
{
    private $_callable;

    public function setCallable($callable)
    {
        $this->_callable = $callable;
    }

    public function run()
    {
        $callable = $this->_callable;
        $callable();
    }
}