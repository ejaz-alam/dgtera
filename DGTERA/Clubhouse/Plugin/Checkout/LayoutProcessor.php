<?php
declare(strict_types=1);

namespace DGTERA\Clubhouse\Plugin\Checkout;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class LayoutProcessor
{
    private $customerMetaData;

    public function __construct(
        CustomerMetadataInterface $customerMetadata
    ) {
        $this->customerMetaData = $customerMetadata;
    }

    public function afterProcess(\Magento\Checkout\Block\Checkout\LayoutProcessor $subject, array $jsLayout)
    {
        $shippingAddress = &$jsLayout['components']['checkout']['children']['steps']['children']['shipping-step']
        ['children']['shippingAddress']['children']['shipping-address-fieldset']['children'];

        $this->addClubhouseProfile($shippingAddress);

        $configuration = $jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']['payment']['children']['payments-list']['children'];
        foreach ($configuration as $paymentGroup => $groupConfig) {
            if (isset($groupConfig['component']) AND $groupConfig['component'] === 'Magento_Checkout/js/view/billing-address') {
                $paymentLayout = &$jsLayout['components']['checkout']['children']['steps']['children']['billing-step']['children']
                ['payment']['children']['payments-list']['children'][$paymentGroup]['children']['form-fields']['children'];
                $this->addClubhouseProfile($paymentLayout);
            }
        }
        return $jsLayout;
    }
    private function addClubhouseProfile(&$jsLayout)
    {
        $enabled  = $this->isEnabled();
        if ($enabled) {
            $jsLayout['clubhouseProfile'] = [
                'component' => 'Magento_Ui/js/form/element/abstract',
                'config' => [
                    'template' => 'ui/form/field',
                    'elementTmpl' => 'ui/form/element/input',
                    'id' => 'clubhouse_profile',
                ],
                'label' => __('Clubhouse Profile'),
                'dataScope' => 'custom_attributes.customer_clubhouse_profile',
                'provider' => 'checkoutProvider',
                'visible' => true,
                'validation' => [
                    'required-entry' => $this->isRequired()
                ],
                'sortOrder' => 100,
                'id' => 'clubhouse_profile'
            ];
        }
    }

    public function isEnabled()
    {
        return $this->_getAttribute('clubhouse_profile') ? (bool)$this->_getAttribute('clubhouse_profile')->isVisible() : false;
    }

    public function isRequired()
    {
        return $this->_getAttribute('clubhouse_profile')
            && (bool)$this->_getAttribute('clubhouse_profile')->isRequired();
    }

    /**
     * @param $attributeCode
     * @return \Magento\Customer\Api\Data\AttributeMetadataInterface|null
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    protected function _getAttribute($attributeCode)
    {
        try {
            return $this->customerMetaData->getAttributeMetadata($attributeCode);
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }
}
