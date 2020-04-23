<?php

namespace SqlProcessing\Utils;

use SqlProcessing\Exceptions\StepConfigurationException;
use SqlProcessing\Steps\PhpStep;
use SqlProcessing\Steps\SqlFile\SqlFileStep;
use SqlProcessing\Steps\Step;

/**
 * Класс Steps - фабрика стандартных шагов процесса
 * @package SqlProcessing\Utils
 *
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
final class Steps
{
    /**
     * Это статический Utils класс-фабрика, создать нельзя
     */
    private function __construct()
    {
    }

    /**
     * Построить шаг из массива.
     *
     * Массив обязательно должен содержать ключ `'class'` для указания
     * типа шага. Указанный класс обязан использовать интерфейс {@see Step}
     *
     * Остальные ключи массива - свойства из указанного класса. Например, для
     * массива:
     * ```
     * array(
     *     'class' => '\SqlProcessing\Steps\ExecuteSqlStatementStep',
     *     'sqlScript' => "INSERT ...",
     * )
     * ```
     * будет создан шаг класса {@see ExecuteSqlStatementStep} с указанным SQL скриптом внутри.
     *
     * Для того, чтобы свойство было распознано этим методом, оно должно иметь сеттер: `set<name>`,
     * где <name> - имя свойства.
     *
     * @param array $array
     * @return Step
     * @throws StepConfigurationException Если указано несуществующее свойство
     * или указанный класс не существует или не наследует интерфейс {@see Step}
     *
     * @author Roman Mitasov <metas_roman@mail.ru>
     */
    public static function fromArray(array $array)
    {
        if (!isset($array['class'])) {
            throw new StepConfigurationException(
                "В описании step через массив должен быть указан 'class'"
            );
        }

        $class = $array['class'];
        unset($array['class']);

        if (!class_exists($class)) {
            throw new StepConfigurationException("Неизвестный класс {$class}");
        }

        if (!is_subclass_of($class, 'SqlProcessing\Steps\Step')) {
            throw new StepConfigurationException(
                "Указанный класс должен иметь интерфейс SqlProcessing\Steps\Step"
            );
        }

        $step = new $class();

        foreach ($array as $property => $value) {
            if (!method_exists($step, 'set' . $property)) {
                throw new StepConfigurationException(
                    "Не найдет сеттер для свойства $property в классе $class"
                );
            }

            $step->{'set' . $property}($value);
        }

        return $step;
    }

    /**
     * Создать шаг из функции PHP
     *
     * @param callable $callable
     * @return PhpStep Шаг, запускающий эту функцию
     * @author Roman Mitasov <metas_roman@mail.ru>
     */
    public static function fromPhpCallable($callable)
    {
        $step = new PhpStep();

        $step->setCallable($callable);

        return $step;
    }

    /**
     * Создать шаг для запуска SQL файла/файлов
     *
     * Указываются только самые востребованные свойства.
     * Если требуется указать {@see SqlRunner} или {@see Logger}, делать это
     * через сеттеры, или использовать фабрику {@see fromArray()}
     *
     * @param string|array $file Имя файла, или массив имён файлов с SQL запросами
     * @param array $params SQL параметры
     * @param string $dir Опционально, папка с SQL файлами
     *
     * @return SqlFileStep
     *
     * @see SqlFileStep::setParams()
     * @see SqlFileStep::setFile()
     * @see SqlFileStep::setDir()
     *
     * @author Roman Mitasov <metas_roman@mail.ru>
     * @noinspection PhpUnused
     */
    public static function sqlFileStep($file, $params = array(), $dir = null)
    {
        $step = new SqlFileStep();

        $step->setFile($file);
        $step->setDir($dir);
        $step->setParams($params);

        return $step;
    }
}