<?php

namespace M2Boilerplate\ServiceWorker\Block;

use M2Boilerplate\ServiceWorker\Helper\Config;
use Magento\Framework\App\View\Deployment\Version\Storage\File;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Framework\View\Element\Template\Context;

class Js extends \Magento\Framework\View\Element\Template
{
    const VERSION_PREFIX = 'm2bp';

    /**
     * @var Config $config
     */
    protected $config;

    /**
     * @var Json
     */
    protected $serializer;
    /**
     * @var File
     */
    private $deployedVersion;

    public function __construct(
        File $deployedVersion,
        Context $context,
        Config $config,
        Json $serializer,
        array $data
    ) {
        $this->config = $config;

        $this->serializer = $serializer;

        $data["cache_lifetime"] = 60 * 60 * 24 * 365;

        parent::__construct($context, $data);
        $this->deployedVersion = $deployedVersion;
    }

    /**
     * Get the provided data encoded as a JSON object.
     *
     * @param mixed $data
     *
     * @return string
     */
    public function jsonEncode($data)
    {
        return $this->serializer->serialize($data);
    }

    /**
     * Get the service worker version string.
     *
     * @return string
     */
    public function getVersion()
    {
        return implode("-", [
            static::VERSION_PREFIX,
            $this->getDeployedVersion()
        ]);
    }

    protected function getDeployedVersion()
    {
        return $this->deployedVersion->load();
    }

    /**
     * Get the offline notification page URL.
     *
     * @return string
     */
    public function getOfflinePageUrl()
    {
        return $this->config->getOfflinePageUrl();
    }

    /**
     * Get the list of URLs for external scripts to import into the service worker.
     *
     * @return string[]
     */
    public function getExternalScriptUrls()
    {
        $scripts = [
            $this->getViewFileUrl("M2Boilerplate_ServiceWorker::js/lib/workbox-core.prod.js"),
            $this->getViewFileUrl("M2Boilerplate_ServiceWorker::js/lib/workbox-background-sync.prod.js"),
            $this->getViewFileUrl("M2Boilerplate_ServiceWorker::js/lib/workbox-routing.prod.js"),
            $this->getViewFileUrl("M2Boilerplate_ServiceWorker::js/lib/workbox-strategies.prod.js"),
            $this->getViewFileUrl("M2Boilerplate_ServiceWorker::js/lib/workbox-precaching.prod.js"),
            $this->getViewFileUrl("M2Boilerplate_ServiceWorker::js/lib/workbox-navigation-preload.prod.js"),
        ];

        if ($this->isGaOfflineEnabled()) {
            $scripts[] = $this->getViewFileUrl("M2Boilerplate_ServiceWorker::js/lib/workbox-offline-ga.prod.js");
        }

        return array_filter($scripts);
    }

    /**
     * Check if Offline Google Analytics features are enabled.
     *
     * @return bool
     */
    public function isGaOfflineEnabled()
    {
        return $this->config->isGaOfflineEnabled();
    }
}
