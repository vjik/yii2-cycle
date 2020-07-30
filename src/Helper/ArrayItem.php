<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Helper;

final class ArrayItem
{

    /**
     * @var string|null
     */
    public $key;

    /**
     * @var mixed
     */
    public $value;

    /**
     * @var bool
     */
    public $wrapValue = true;

    /**
     * @var bool
     */
    public $wrapKey;

    public function __construct(?string $key, $value = null, bool $wrapKey = false)
    {
        $this->key = $key;
        $this->value = $value;
        $this->wrapKey = $wrapKey;
    }

    public function __toString()
    {
        $result = '';
        if ($this->key !== null) {
            $result = $this->wrapKey ? "'{$this->key}' => " : "{$this->key} => ";
        }
        return $result . $this->renderValue($this->value);
    }

    private function renderValue($value)
    {
        if ($value === null) {
            return 'null';
        }
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
        if (is_array($value)) {
            if (count($value) === 0) {
                return '[]';
            }
            $result = '[';
            foreach ($value as $key => $item) {
                $result .= "\n";
                if (!$item instanceof ArrayItem) {
                    $result .= is_int($key) ? "{$key} => " : "'{$key}' => ";
                }
                $result .= $this->renderValue($item) . ',';
            }
            return str_replace("\n", "\n    ", $result) . "\n]";
        }
        if (!$this->wrapValue || is_int($value) || $value instanceof ArrayItem) {
            return (string)$value;
        }
        return "'" . addslashes((string)$value) . "'";
    }
}
