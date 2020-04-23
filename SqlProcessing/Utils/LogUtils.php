<?php
/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */

namespace SqlProcessing\Utils;


use SqlProcessing\Log\Logger;

class LogUtils
{

    public static function paramsRequired($message)
    {
        return (bool) preg_match('~(?<!\\\\){.+}~', $message);
    }

    public static function prepareMessage($message, array $params)
    {
        $prepared_params = array();

        foreach ($params as $placeholder => $param) {
            if (is_object($param)) {
                $param = strval($param);
            } elseif (!is_scalar($param)) {
                $param = print_r($param, true);
            } else {
                $param = strval($param);
            }

            $prepared_params["{{$placeholder}}"] = $param;
        }

        $message = strtr($message, $prepared_params);

        return $message;
    }


    public static function logSqlRun(Logger $log, $sql, $params)
    {
        $msg = "Запуск SQL: {0}";
        if ($params) {
            $msg .= " С параметрами: {1}";
        }
        $log->debug($msg, array($sql, $params));
    }
}