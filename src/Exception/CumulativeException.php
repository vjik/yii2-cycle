<?php

declare(strict_types=1);

namespace Vjik\Yii2\Cycle\Exception;

use RuntimeException;
use Throwable;

class CumulativeException extends RuntimeException
{
    /** @var Throwable[] */
    private $exceptions;

    public function __construct(Throwable ...$exceptions)
    {
        $this->exceptions = $exceptions;
        $count = count($exceptions);
        $message = $count === 1 ? 'One exception was thrown.' : $count . ' exceptions were thrown.';
        parent::__construct($message . $this->getMessageDetails());
    }

    /**
     * @return Throwable[]
     */
    public function getExceptions(): array
    {
        return $this->exceptions;
    }

    private function getMessageDetails(): string
    {
        $result = '';
        $num = 0;
        foreach ($this->exceptions as $exception) {
            $result .= sprintf(
                "\n\n%d) %s:%s\n[%s] #%d: %s",
                ++$num,
                $exception->getFile(),
                $exception->getLine(),
                get_class($exception),
                $exception->getCode(),
                $exception->getMessage()
            );
        }
        return $result;
    }
}
