<?php

namespace WebAppInstaller\Contracts;

interface ExecutionResultInterface {

    /**
     * True if execution is successful, false otherwise
     * @return bool 
     */
    public function getStatus(): bool;

    /**
     * Array of messages (success or failure)
     * @return array
     */
    public function getMessages(): array;
}
