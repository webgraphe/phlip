<?php

namespace Webgraphe\Phlip;

class IOMode
{
    /** @var int */
    const STAT_MODE_MASK = 0170000;

    /** @var array */
    private $stat;


    /**
     * @param resource $resource
     */
    public function __construct($resource)
    {
        $this->stat = fstat($resource);
    }

    public function isNamedPipe(): bool
    {
        return $this->stat['mode'] & self::STAT_MODE_MASK === 0010000;
    }

    public function isCharacterSpecialDevice(): bool
    {
        return $this->stat['mode'] & self::STAT_MODE_MASK === 0020000;
    }

    public function isDirectory(): bool
    {
        return $this->stat['mode'] & self::STAT_MODE_MASK === 0040000;
    }

    public function isBlockSpecialDevice(): bool
    {
        return $this->stat['mode'] & self::STAT_MODE_MASK === 0060000;
    }

    public function isRegularFile(): bool
    {
        return $this->stat['mode'] & self::STAT_MODE_MASK === 0100000;
    }

    public function isSymbolicLink(): bool
    {
        return $this->stat['mode'] & self::STAT_MODE_MASK === 0120000;
    }

    public function isSocket(): bool
    {
        return $this->stat['mode'] & self::STAT_MODE_MASK === 0140000;
    }

    public function getSize(): int
    {
        return (int)$this->stat['size'];
    }
}
