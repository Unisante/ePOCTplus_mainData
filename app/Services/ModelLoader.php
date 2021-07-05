<?php

namespace App\Services;

abstract class ModelLoader {
    // TODO default empty keys/values ?
    abstract public function getKeys();
    abstract public function getValues();

    abstract public function model();

    public function load() {
        $record = $this->model()::firstOrCreate(
            $this->getKeys(),
            $this->getValues(),
        );
        $record->save();
        return $record;
    }
}