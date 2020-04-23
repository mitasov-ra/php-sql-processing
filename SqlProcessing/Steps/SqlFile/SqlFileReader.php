<?php
/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */

namespace SqlProcessing\Steps\SqlFile;


use SqlProcessing\Exceptions\SqlFileParseException;
use SqlProcessing\Utils\Strings;

/**
 * Класс SqlFileReader
 *
 * Принимает ресурс файла, построчно его читает и возвращает
 * соответствующие токены.
 *
 * Этакий лексический анализатор.
 *
 * @package SqlProcessing\Steps\SqlFile
 *
 * @author Roman Mitasov <metas_roman@mail.ru>
 */
class SqlFileReader
{
    const START = 1;
    const TAG = 2;

    public $count;
    public $file;
    public $read_line = true;
    public $line;
    public $eof = false;

    public function __construct($file)
    {
        $this->file  = $file;
        $this->count = 0;
    }

    /**
     * Прочитать файл с места остановки и венуть новый токен
     *
     * @return Token|null
     * @throws SqlFileParseException
     * @author Roman Mitasov <metas_roman@mail.ru>
     */
    public function nextToken()
    {
        $file            = $this->file;
        $current_tag     = null;
        $current_content = null;
        $script_content  = null;

        while (true) {
            // если был достигнут конец файла - возращаем токен конца строки
            if ($this->eof) {
                if ($current_tag !== null) {
                    return Token::Tag($current_tag, $current_content, $this->count);
                }

                if ($script_content !== null) {
                    return Token::Script($script_content, $this->count);
                }

                return Token::End($this->count);
            }

            // предыдущая итерация завершила обработку строки,
            // переход к следующей
            if ($this->read_line) {
                // если достигнут конец файла, выставить флаг и
                // на новую итерацию
                if (feof($file)) {
                    $this->eof       = true;
                    $this->read_line = false;
                    continue;
                }

                // читаем строку
                $this->line = trim(fgets($file));
                $this->count++;

                // если строка пустая, сразу к следующей
                if ($this->line === '') {
                    continue;
                }

                // флажок на false, чтобы больше не читалась строка, пока него не поменяют
                $this->read_line = false;
            }

            // если строка - комментарий с тегом
            if (self::checkTag($this->line, $tag, $content)) {
                if ($script_content !== null) {
                    $this->read_line = false;
                    return Token::Script($script_content, $this->count - 1);
                }

                if ($tag == Token::TAG_CONTINUE) {
                    if ($current_tag === null) {
                        throw new SqlFileParseException(
                            "Тег @ без предшествующего тега",
                            $this->count
                        );
                    }

                    $current_content .= ' ' . $content;
                    $this->read_line = true;
                    continue;
                }

                if ($current_tag !== null && $tag != $current_tag) {
                    $this->read_line = false;
                    return Token::Tag($current_tag, $current_content, $this->count - 1);
                }

                $current_tag     = $tag;
                $current_content = $content;
                $this->read_line = true;
                continue;
            }

            // строка не тег, но какой-то тег уже в памяти - возвращаем его,
            // следующую строку не читать
            if ($current_tag !== null) {
                $this->read_line = false;
                return Token::Tag($current_tag, $current_content, $this->count - 1);
            }

            // если строка - просто комментарий - пропустить
            if (Strings::startsWith($this->line, '--')) {
                $this->read_line = true;
                continue;
            }

            // не тег и не коммент, значит SQL, если до этого
            // не было SQL - сохраняем строку и идём к следующей
            if ($script_content === null) {
                $script_content = $this->line;
                $this->read_line = true;
                continue;
            }

            // а иначе - добавляем к уже имеющемуся SQL строку и переходим к следующей
            $script_content .= PHP_EOL . $this->line;
            $this->read_line = true;
        }

        return null;
    }

    private static function checkTag($string, &$tag, &$content)
    {
        $match = preg_match('~^--\s*(@[\w\d_\-]*)(?:\s+(.+))?$~i', $string, $matches);

        if (!$match) {
            return false;
        }

        $tag     = strtolower($matches[1]);
        $content = isset($matches[2]) ? trim($matches[2]) : '';


        return $match;
    }
}