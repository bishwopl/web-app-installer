<?php

namespace WebAppInstaller\Entity;

use WebAppInstaller\Contracts\ExecutionResultInterface;

class ExecutionResult implements ExecutionResultInterface {

    /**
     * True if execution is successful, false otherwise
     * @var bool 
     */
    protected $status;

    /**
     * @var array
     */
    protected $messages;

    private function __construct() {
        
    }

    /**
     * @param bool $status
     * @param array $messages
     * @return \WebAppInstaller\Entity\ExecutionResult
     */
    public static function create(bool $status, array $messages = []): ExecutionResult {
        $result = new ExecutionResult();
        $result->status = $status;
        $result->messages = $messages;
        return $result;
    }

    public function getStatus(): bool {
        return $this->status;
    }

    public function getMessages(): array {
        return $this->messages;
    }

}
