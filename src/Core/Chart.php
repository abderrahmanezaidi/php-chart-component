<?php
namespace ChartComponent\Core;

abstract class Chart {
    protected $data;
    protected $options;

    public function __construct(array $data, array $options = []) {
        $this->data = $data;
        $this->options = $options;
    }

    abstract public function render(): string;

    public function toJson(): string {
        return json_encode([
            'data' => $this->data,
            'options' => $this->options
        ]);
    }
}