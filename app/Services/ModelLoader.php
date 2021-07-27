<?php

namespace App\Services;

use DateTime;
use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

abstract class ModelLoader {
    private $rawData;

    protected function getKeys() { return $this->keyValuesFromConfig('keys'); }
    protected function getValues() { return $this->keyValuesFromConfig('values'); }

    abstract protected function model();
    abstract protected function configName();

    /**
     * Constructor
     *
     * @param array $rawData data that contains keys specified in the configuration file
     */
    public function __construct($rawData)
    {
        $this->rawData = $rawData;
        
        foreach ($this->config() as $category => $pairs) {
            foreach ($pairs as $configValue) {
                $key = is_array($configValue) ? $configValue['key'] : $configValue;

                if (!is_array($configValue) || array_search('optional', $configValue['modifiers']) === false) {
                    $modelTitle = $this->configName();
                    if (!array_key_exists($key, $this->rawData)) {
                        throw new InvalidArgumentException("Missing key '$key' on data for '$modelTitle'");
                    }
        
                    if (is_array($configValue) && array_search('language', $configValue['modifiers']) !== false) {
                        $language = Config::get('medal-data.global.language');
                        if (!array_key_exists($language, $this->rawData[$key])) {
                            throw new InvalidArgumentException("Missing language dependent ('$language') value for key '$key' on data for '$modelTitle'");
                        }
                    }
                }
                
            }
        }
    }

    private function config() {
        return Config::get('medal-data.case_json_properties')[$this->configName()];
    }

    protected function valueFromConfig($category, $property) {
        return $this->rawData[$this->config()[$category][$property]] ?? null;
    }

    protected function languageValueFromConfig($property) {
        return $this->rawData[$property][Config::get('medal-data.global.language')] ?? null;
    }

    protected function keyValuesFromConfig($category) {
        $properties = array_keys($this->config()[$category] ?? []);
        return array_combine(
            $properties,
            array_map(function($p) use ($category) {
                $config = $this->config()[$category][$p];
                $key = is_array($config) ? $config['key'] : $config;
                
                $value = null;
                if (is_array($config) && array_search('language', $config['modifiers']) !== false) {
                    $value = $this->languageValueFromConfig($key);
                } else if (is_array($config) && array_search('datetime', $config['modifiers']) !== false) {
                    $value = array_key_exists($key, $this->rawData) ? new DateTime($this->rawData[$key]) : null;
                } else if (is_array($config) && array_search('datetime-epoch', $config['modifiers']) !== false) {
                    $value = array_key_exists($key, $this->rawData) ? new DateTime("@" . intdiv($this->rawData[$key], 1000)) : null;
                } else {
                    $value = $this->rawData[$key] ?? null;
                }

                if ($value === null && is_array($config) && array_search('optional', $config['modifiers']) !== false) {
                    switch ($config['type'] ?? null) {
                        case 'string':
                            $value = '';
                            break;
                        case 'int':
                            $value = 0;
                            break;
                        case 'array':
                            $value = [];
                        default:
                            break;
                    }
                }

                return $value;
            }, $properties)
        );
    }

    /**
     * Create a model instance based on the data that was provided
     *
     * @return Model
     */
    public function load() {
        $record = $this->model()::firstOrCreate(
            $this->getKeys(),
            $this->getValues(),
        );
        $record->save();
        return $record;
    }
}