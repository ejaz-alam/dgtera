<?php
declare(strict_types=1);

namespace DGTERA\Clubhouse\Plugin\Sales\Api;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Psr\Log\LoggerInterface;

class OrderManagementInterfacePlugin
{

    private $quoteRepository;

    private $logger;

    public function __construct(
        CartRepositoryInterface $quoteFactory,
        LoggerInterface $logger
    ) {
        $this->quoteRepository = $quoteFactory;
        $this->logger = $logger;
    }

    public function beforePlace(OrderManagementInterface $subject, OrderInterface $order): array
    {
        $quoteId = (int)$order->getQuoteId();
        if ($quoteId) {
            try {
                $quote = $this->quoteRepository->get($quoteId);
            } catch (NoSuchEntityException $e) {
                $this->logger->critical($e->getMessage());
                return [$order];
            }
            $clubhouseProfile = $quote->getClubhouseProfile();
            $order->setClubhouseProfile($clubhouseProfile);
        }
        return [$order];
    }
}
