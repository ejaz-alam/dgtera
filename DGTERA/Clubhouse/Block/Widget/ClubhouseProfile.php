<?php

declare(strict_types=1);

namespace DGTERA\Clubhouse\Block\Widget;

use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Customer\Block\Widget\AbstractWidget;
use Magento\Customer\Helper\Address;
use Magento\Framework\View\Element\Template\Context;

class ClubhouseProfile extends AbstractWidget
{
    public function __construct(
        Context $context,
        Address $addressHelper,
        CustomerMetadataInterface $customerMetadata,
        array $data = []
    ) {
        parent::__construct($context, $addressHelper, $customerMetadata, $data);
    }

    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('DGTERA_Clubhouse::widget/clubhouse-profile.phtml');
    }
    public function isEnabled()
    {
        return $this->_getAttribute('clubhouse_profile')
            && (bool)$this->_getAttribute('clubhouse_profile')->isVisible();
    }

    /**
     * Check if attribute marked as required
     *
     * @return bool
     */
    public function isRequired()
    {
        return $this->_getAttribute('clubhouse_profile')
            && (bool)$this->_getAttribute('clubhouse_profile')->isRequired();
    }

    public function getStoreLabel($attributeCode)
    {
        $attribute = $this->_getAttribute($attributeCode);
        return $attribute ? __($attribute->getStoreLabel()) : '';
    }
}
