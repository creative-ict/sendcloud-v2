<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 *
 * Creative CT - fix implementation for core problem magento
 * This is a patch/hotfix to solve the broken M2.4.2 integration service as defined under SC-106
 *
 * Override of: vendor/magento/module-integration/Controller/Adminhtml/Integration/TokensExchange.php
 * Source fix: https://github.com/magento/magento2/commit/68d20230ca9d6198fa3093eb174e24aff47b1646
 * Source issue: https://github.com/magento/magento2/issues/33013
 *
 * TODO: remove this override once the fix makes it to core M2.x.* code; ref to SC-106!
 */
declare(strict_types=1);

namespace SendCloud\SendCloudV2\Controller\Adminhtml\Integration;

use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Oauth\Exception;
use Magento\Integration\Model\Integration as IntegrationModel;
use Magento\Integration\Controller\Adminhtml\Integration\TokensExchange as TokensExchangeOrigin;
/**
 * Tokens Exchange for integration
 */
class TokensExchange extends TokensExchangeOrigin implements HttpGetActionInterface
{
    /**
     * Let the admin know that integration has been sent for activation and token exchange is in process.
     *
     * @param bool $isReauthorize
     * @param string $integrationName
     * @return void
     */
    protected function _setActivationInProcessMsg($isReauthorize, $integrationName)
    {
        $integrationName = $this->escaper->escapeHtml($integrationName);
        $msg = $isReauthorize ? __(
            "Integration '%1' has been sent for re-authorization.",
            $integrationName
        ) : __(
            "Integration '%1' has been sent for activation.",
            $integrationName
        );
        $this->messageManager->addNotice($msg);
    }

    /**
     * Post consumer credentials for Oauth integration.
     *
     * @return void
     */
    public function execute()
    {
        try {
            $integrationId = $this->getRequest()->getParam(self::PARAM_INTEGRATION_ID);
            $isReauthorize = (bool)$this->getRequest()->getParam(self::PARAM_REAUTHORIZE, 0);
            $integration = $this->_integrationService->get($integrationId);
            if ($isReauthorize) {
                /** Remove existing token associated with consumer before issuing a new one. */
                $this->_oauthService->deleteIntegrationToken($integration->getConsumerId());
                $integration->setStatus(IntegrationModel::STATUS_INACTIVE)->save();
            }
            //Integration chooses to use Oauth for token exchange
            $this->_oauthService->postToConsumer($integration->getConsumerId(), $integration->getEndpoint());
            /** Generate JS popup content */
            $this->_view->loadLayout(false);
            // Activation or authorization is done only when the Oauth token exchange completes
            $this->_setActivationInProcessMsg($isReauthorize, $integration->getName());
            $this->_view->renderLayout();
            $popupContent = $this->_response->getBody();
            $consumer = $this->_oauthService->loadConsumer($integration->getConsumerId());
            if (!$consumer->getId()) {
                throw new Exception(
                    __(
                        'A consumer with "%1" ID doesn\'t exist. Verify the ID and try again.',
                        $integration->getConsumerId()
                    )
                );
            }
            /** Initialize response body */
            $result = [
                IntegrationModel::IDENTITY_LINK_URL => $integration->getIdentityLinkUrl(),
                'oauth_consumer_key' => $consumer->getKey(),
                'popup_content' => $popupContent,
            ];
            $this->getResponse()->representJson($this->jsonHelper->jsonEncode($result));
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
            $this->_redirect('*/*');
            return;
        } catch (\Exception $e) {
            $this->_logger->critical($e);
            $this->messageManager->addErrorMessage(__('Internal error. Check exception log for details.'));
            $this->_redirect('*/*');
            return;
        }
    }
}
