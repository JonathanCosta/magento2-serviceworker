<?php

namespace M2Boilerplate\ServiceWorker\Helper;

use Magento\Cms\Helper\Page;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\ScopeInterface;

class Config extends \Magento\Framework\App\Helper\AbstractHelper
{
    const XML_PATH_ENABLE = "web/serviceworker/enable";
    const XML_PATH_OFFLINE_PAGE = "web/serviceworker/offline_page";
    const XML_PATH_CUSTOM_STRATEGIES = "web/serviceworker/custom_strategies";
    const XML_PATH_GA_OFFLINE_ENABLE = "web/serviceworker/ga_offline_enable";

    const PATH_WILDCARD_SYMBOL = "*";

    const SERVICEWORKER_ENDPOINT = "serviceworker.js";

    /** @var Page $cmsPageHelper */
    protected $cmsPageHelper;

    /** @var DeploymentConfig $deploymentConfig */
    protected $deploymentConfig;

    /** @var Json $serializer */
    protected $serializer;

    /**
     * Config constructor.
     *
     * @param Context        $context
     * @param Page                     $cmsPageHelper
     * @param DeploymentConfig      $deploymentConfig
     * @param Json $serializer
     */
    public function __construct(
        Context $context,
        Page $cmsPageHelper,
        DeploymentConfig $deploymentConfig,
        Json $serializer
    ) {
        parent::__construct($context);

        $this->cmsPageHelper = $cmsPageHelper;
        $this->deploymentConfig = $deploymentConfig;
        $this->serializer = $serializer;
    }

    /**
     * Check if the service worker is enabled on the given store.
     *
     * @param string $store
     *
     * @return bool
     */
    public function isEnabled($store = null)
    {
        return $this->scopeConfig->isSetFlag(static::XML_PATH_ENABLE, ScopeInterface::SCOPE_STORE, $store);
    }

    /**
     * Get the URL for the Offline Notification page.
     *
     * Warning: Always returns the URL for the current store scope due to Magento_Cms helper limitation.
     *
     * @return string
     */
    public function getOfflinePageUrl()
    {
        if ($identifier = $this->scopeConfig->getValue(static::XML_PATH_OFFLINE_PAGE, ScopeInterface::SCOPE_STORE)) {
            return $this->cmsPageHelper->getPageUrl($identifier);
        }
        return null;
    }



    /**
     * Check if Offline Google Analytics features are enabled.
     *
     * @param string $store
     *
     * @return bool
     */
    public function isGaOfflineEnabled($store = null)
    {
        return $this->scopeConfig->isSetFlag(static::XML_PATH_GA_OFFLINE_ENABLE, ScopeInterface::SCOPE_STORE, $store);
    }
}
