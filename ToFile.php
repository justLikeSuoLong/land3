<?php

namespace Land3;

use Closure;
use Psr\Log\InvalidArgumentException;
use Psr\Log\LoggerTrait;
use Psr\Log\LoggerInterface;

class ToFile implements LoggerInterface
{
    use LoggerTrait;

    private Closure $policy;

    public function setPolicy(Closure $policy)
    {
        $this->policy = $policy;
    }

    /**
     * @param mixed $level
     * @param string $message
     * @param array $context
     *
     * @throws
     */
    public function log($level, $message, array $context = array())
    {
        // TODO: Implement log() method.
        if (!method_exists($this, $level)) {
            throw new InvalidArgumentException();
        }
        if (count($context)) {
            $message = Helper::format($message, $context);
        }
        $policy = $this->policy;
        $target = (string)$policy();
        file_put_contents($target, "$level $message\n", LOCK_EX | FILE_APPEND);
    }
}