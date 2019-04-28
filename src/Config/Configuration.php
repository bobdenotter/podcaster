<?php

declare(strict_types=1);

namespace App\Config;

use Symfony\Component\Yaml\Yaml;

class Configuration
{
    private $data = [];
    private $configFilename;

    public function __construct()
    {
        $this->configFilename = dirname(dirname(__DIR__)) . '/config/config.yml';
        $this->initialize();
    }

    private function initialize()
    {
        $this->config = Yaml::parseFile($this->configFilename);
        $this->modifiedAt = filemtime($this->configFilename);
    }


    public function get(): array
    {
        return $this->config;
    }

    public function set(array $config)
    {
        $this->config = $config;
    }

    public function modifiedAt()
    {
        return $this->modifiedAt;
    }
}
