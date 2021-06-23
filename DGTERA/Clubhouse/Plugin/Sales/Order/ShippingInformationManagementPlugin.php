<?php
declare(strict_types=1);


namespace DGTERA\Clubhouse\Plugin\Sales\Order;

use Magento\Checkout\Api\Data\ShippingInformationInterface;
use Magento\Checkout\Model\ShippingInformationManagement;
use Magento\Quote\Model\QuoteRepository;
use Magento\Quote\Api\Data\AddressInterface;

class ShippingInformationManagementPlugin
{
    private $quoteRepository;
    /**
     * @var AddressInterface
     */
    private $addressInterface;

    public function __construct(
        QuoteRepository $quoteRepository,
        AddressInterface $addressInterface
    ) {
        $this->quoteRepository = $quoteRepository;
        $this->addressInterface = $addressInterface;
    }

    public function beforeSaveAddressInformation(
        ShippingInformationManagement $subject,
        $cartId,
        ShippingInformationInterface $addressInformation
    ) {
        $extAttributes = $addressInformation->getShippingAddress()->getExtensionAttributes();
        $extAttributesAddress = $addressInformation->getShippingAddress()->getExtensionAttributes();
        $clubhouseProfile = $extAttributes->getClubhouseProfile();
        $quote = $this->quoteRepository->getActive($cartId);
        $quote->setClubhouseProfile($clubhouseProfile);
    }
}
