<?php

namespace WebAppInstaller\Service;

use WebAppInstaller\Contracts\ConfigSaverInterface;
use WebAppInstaller\Contracts\ExecutionResultInterface;
use WebAppInstaller\Entity\ExecutionResult;
use Laminas\ConfigAggregator\ConfigAggregator;
use Laminas\ConfigAggregator\ArrayProvider;
use Laminas\Config\Writer\PhpArray as ConfigWriter;
use ArrayAccess;
use Exception;

class ConfigSaverService implements ConfigSaverInterface, ArrayAccess {

    /**
     * @var string
     */
    protected $fileName;

    public function __construct(string $fileName) {
        $this->fileName = $fileName;
    }

    public function save(array $config): ExecutionResultInterface {
        $status = true;
        $message = [];
        try{
            $writer = new ConfigWriter();
            $currentConfig = $this->getCurrentValues();
            $configAggregator = new ConfigAggregator([
                new ArrayProvider($currentConfig),
                new ArrayProvider($config)
            ]);
            $writer->setUseBracketArraySyntax(true)
                    ->setUseClassNameScalars(true)
                    ->toFile($this->fileName, $configAggregator->getMergedConfig());
        } catch (Exception $ex) {
            $status = false;
            $message[] = $ex->getMessage();
        }
        return ExecutionResult::create($status, $message);
    }

    private function getCurrentValues(): array {
        $ret = [];
        if (is_file($this->fileName)) {
            $ret = require $this->fileName;
        }
        return $ret;
    }

    public function clear(): void {
        $writer = new ConfigWriter();
        $writer->toFile($this->fileName, []);
        return;
    }

    public function offsetExists($offset): bool {
        $currentVal = $this->getCurrentValues();
        return array_key_exists($offset, $currentVal);
    }

    public function offsetGet($offset) {
        $ret = null;
        $currentVal = $this->getCurrentValues();
        if (array_key_exists($offset, $currentVal)) {
            $ret = $currentVal[$offset];
        }
        return $ret;
    }

    public function offsetSet($offset, $value): void {
        $this->save([$offset => $value]);
    }

    public function offsetUnset($offset): void {
        $currentVal = $this->getCurrentValues();
        $this->clear();
        if (array_key_exists($offset, $currentVal)) {
            unset($currentVal[$offset]);
        }
        $this->save($currentVal);
    }

}
