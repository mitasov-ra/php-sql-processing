<?php

namespace SqlProcessing;

use Exception;
use SqlProcessing\Exceptions\StepConfigurationException;
use SqlProcessing\Log\Loggable;
use SqlProcessing\Log\Logger;
use SqlProcessing\Log\VoidLogger;
use SqlProcessing\Runners\SqlRunner;
use SqlProcessing\Steps\SqlFile\SqlFileStep;
use SqlProcessing\Steps\SqlStep;
use SqlProcessing\Steps\Step;
use SqlProcessing\Utils\Steps;

/**
 * Стандартный класс для запуска SQL-процесса
 *
 * Позволяет добавлять шаги, указывать логгер и SQL-раннер по умолчанию
 * для шагов.
 *
 * @see Logger
 * @see SqlRunner
 * @see Step
 * @see setSteps()
 *
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
class CommonSqlProcess implements SqlProcess
{
    /** @var Step[] */
    private $_steps = array();

    /** @var Logger */
    private $_defaultLogger;

    /** @var SqlRunner */
    private $_defaultSqlRunner;

    /** @var string */
    private $_defaultSqlFilesDir;

    public function __construct()
    {
        $this->_defaultLogger = new VoidLogger();
    }

    /**
     * @param Logger $defaultLogger
     */
    public function setDefaultLogger($defaultLogger)
    {
        $this->_defaultLogger = $defaultLogger;
    }

    /**
     * @param SqlRunner $defaultSqlRunner
     */
    public function setDefaultSqlRunner($defaultSqlRunner)
    {
        $this->_defaultSqlRunner = $defaultSqlRunner;
    }

        /**
     * @return string
     */
    public function getDefaultSqlFilesDir()
    {
        return $this->_defaultSqlFilesDir;
    }

    /**
     * @param string $defaultSqlFilesDir
     */
    public function setDefaultSqlFilesDir($defaultSqlFilesDir)
    {
        $this->_defaultSqlFilesDir = $defaultSqlFilesDir;
    }

    /**
     * Добавить шаг
     *
     * Если шаг является {@see Loggable}, и его логгер равер `null`, то
     * в него вставится {@see setDefaultLogger() логгер по умолчанию}.
     *
     * Аналогично, если шаг является {@see SqlStep}, то вставится
     * {@see setDefaultSqlRunner() SqlRunner по умолчанию}.
     *
     * А если шаг ещё и {@see SqlFileStep}, то вставится и
     * {@see setDefaultSqlFilesDir() папка SQL-файлов по умолчанию}.
     *
     * @param Step $step
     * @return void
     * @author Roman Mitasov <metas_roman@mail.ru>
     */
    public function addStep(Step $step)
    {
        if ($step instanceof Loggable && $step->getLogger() === null) {
            $step->setLogger($this->_defaultLogger);
        }

        if ($step instanceof SqlStep && $step->getSqlRunner() === null) {
            $step->setSqlRunner($this->_defaultSqlRunner);

            if ($step instanceof SqlFileStep && $step->getDir() === null) {
                $step->setDir($this->_defaultSqlFilesDir);
            }
        }

        $this->_steps[] = $step;
    }

    /**
     * Добавить массив шагов
     *
     * @param Step[] $steps
     * @return void
     *
     * @uses addStep()
     *
     * @author Roman Mitasov <metas_roman@mail.ru>
     */
    public function addSteps(array $steps)
    {
        foreach ($steps as $step) {
            $this->addStep($step);
        }
    }

    /**
     * Добавить шаги одним смешанным массивом-конфигурацией
     *
     * Элементами массива могут быть массивы, php-функции и готовые шаги.
     *
     * При этом для каждого будет использован соответствующий билдер из
     * {@see Steps}
     *
     * @param array $config
     * @return void
     *
     * @uses Steps::fromArray()
     * @uses Steps::fromPhpCallable()
     * @uses addStep()
     *
     * @throws StepConfigurationException Если не найдётся нужный билдер для элемента массива
     * @author Roman Mitasov <metas_roman@mail.ru>
     */
    public function addStepsAsMixedArray(array $config)
    {
        foreach ($config as $entry) {
            if (is_callable($entry)) {
                $this->addStep(Steps::fromPhpCallable($entry));
                continue;
            }

            if (is_object($entry) && $entry instanceof Step) {
                $this->addStep($entry);
                continue;
            }

            if (is_array($entry)) {
                $this->addStep(Steps::fromArray($entry));
                continue;
            }

            throw new StepConfigurationException(
                "Недопустимый элемент в массиве конфигурации Steps"
            );
        }
    }

    /**
     * Запустить SQL процесс
     *
     * Запустит каждый шаг в порядке их добавления
     *
     * @return void
     *
     * @uses Step::run()
     *
     * @throws Exception
     * @author Roman Mitasov <metas_roman@mail.ru>
     */
    public final function run()
    {
        foreach ($this->_steps as $step) {
            try {
                $step->run();
            } catch (Exception $e) {
                $this->_defaultLogger->error("Ошибка при запуске шага процесса!"
                    . PHP_EOL
                    . "Ошибка: {$e->getMessage()}"
                    . PHP_EOL
                    . " Stack trace: {$e->getTraceAsString()}");
                throw $e;
            }
        }
    }

}