<?php

namespace SendCloud\SendCloudV2\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\Module\Dir;
use Magento\Framework\Module\ResourceInterface;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Store\Model\ScopeInterface;

class Backend extends AbstractHelper
{
    /**
     * @var Dir
     */
    public $moduleDir;

    /**
     * @var \Magento\Framework\Filesystem\DriverInterface
     */
    public $filesystemDriver;

    /**
     * @var ResourceInterface
     */
    private $moduleResource;

    /**
     * @var Json
     */
    private $json;

    /**
     * Configuration constructor.
     *
     * @param ResourceInterface $moduleResource
     * @param Dir $moduleDir
     * @param File $filesystemDriver
     * @param Json $json
     */
    public function __construct(
        ResourceInterface $moduleResource,
        Dir $moduleDir,
        File $filesystemDriver,
        Json $json
    ) {
        $this->moduleResource = $moduleResource;
        $this->moduleDir = $moduleDir;
        $this->filesystemDriver = $filesystemDriver;
        $this->json = $json;
    }

    public function getModuleVersion()
    {
        $composerJsonFile = $this->moduleDir->getDir('SendCloud_SendCloudV2').DIRECTORY_SEPARATOR.'composer.json';
        if ($this->filesystemDriver->isExists($composerJsonFile)) {
            $jsonContent = $this->filesystemDriver->fileGetContents($composerJsonFile);
            $composerJson = $this->json->unserialize($jsonContent);
            $version = $composerJson['version'];
        } else {
            $version = $this->moduleResource->getDbVersion('SendCloud_SendCloudV2');
        }
        return $version;
    }

    /**
     * @return bool
     */
    public function checkForScriptUrl()
    {
        $isScriptUrlDefined = true;
        $scriptUrl = $this->scopeConfig->getValue('sendcloud/sendcloudv2/script_url', ScopeInterface::SCOPE_STORE);

        if ($scriptUrl == '' || $scriptUrl == null) {
            $this->sendCloudLogger->debug('The option service point is not active in Sendcloud');
            $isScriptUrlDefined = false;
        }

        return $isScriptUrlDefined;
    }

    /**
     * @return bool|mixed
     */
    public function checkIfModuleIsActive()
    {
        $isActive = $this->scopeConfig->getValue(
            'sendcloudv2/general/enable',
            ScopeInterface::SCOPE_STORE
        );

        return $isActive;
    }
}
