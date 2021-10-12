<?php
namespace Drupal\wbumenudomain\Services;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;

class WbumenudomainSiteconfig
{

    protected $ConfigFactory;

    protected $idConfig = null;

    /**
     *
     * @param ConfigFactoryInterface $config_factory
     */
    public function __construct(ConfigFactoryInterface $config_factory)
    {
        $this->ConfigFactory = $config_factory;
    }

    /**
     * -
     */
    public function getConfigs()
    {
        return $this->ConfigFactory->listAll('domain.config');
    }

    public function getValue($key = null)
    {
        $this->idConfig = $key;
        if ($key === null)
            return null;
        $val = $this->ConfigFactory->get($key);
        return $this->get($val);
    }

    public function SetIdConfig($key)
    {
        $this->idConfig = $key;
    }

    public function SaveValue($key, $value)
    {
        // dump($key, $value);
        if ($this->idConfig) {
            $conf = $this->ConfigFactory->getEditable($this->idConfig);
            $conf->set($key, $value)->save();
        }
    }

    /**
     *
     * @param
     *
     */
    protected function get(ImmutableConfig $conf)
    {
        return $conf->getRawData();
    }
}