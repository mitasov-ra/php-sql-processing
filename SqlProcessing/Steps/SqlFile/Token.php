<?php
/**
 * @author Roman Mitasov <metas_roman@mail.ru>
 */

namespace SqlProcessing\Steps\SqlFile;


class Token
{
    const TAG_PROCESS = '@process';
    const TAG_CONTINUE = '@';
    const TAG_PARAMS = '@params';
    const TAG_LOG = '@log';
    const TAG_QUERY = '@query';
    const TAG_LOG_AFFECTED = '@log-affected';

    const TAG = 1;
    const SCRIPT = 2;
    const END = 3;

    private $name;

    private $content;

    private $type;

    private $line;

    /**
     * Token constructor.
     * @param $line
     * @param $type
     * @param $content
     * @param $name
     */
    private function __construct($line, $type, $content = null, $name = null)
    {
        $this->name    = $name;
        $this->content = $content;
        $this->line    = $line;
        $this->type    = $type;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getLine()
    {
        return $this->line;
    }

    public function is($name)
    {
        return $name === $this->name;
    }

    public static function Tag($name, $content, $line)
    {
        return new Token($line, self::TAG, $content, $name);
    }

    public static function Script($content, $line)
    {
        return new Token($line, self::SCRIPT, $content);
    }

    public static function End($line)
    {
        return new Token($line, self::END);
    }

    public function __toString()
    {
        switch ($this->type) {
            case self::TAG:
                return "[$this->line] @$this->name{$this->content}";
            case self::SCRIPT:
                return "[$this->line] SQL: $this->content";
            case self::END:
                return "[$this->line] EOF";
            default:
                return "[$this->line] Unknown";
        }
    }
}