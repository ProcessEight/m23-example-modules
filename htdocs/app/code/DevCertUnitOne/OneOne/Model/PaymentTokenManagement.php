<?php
/**
 * Process Eight
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEight for more information.
 *
 * @package     m23-example-modules
 * @copyright   Copyright (c) 2019 Process Eight
 * @author      Process Eight
 *
 */

namespace DevCertUnitOne\OneOne\Model;

use Magento\Framework\Api\FilterBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Sales\Api\Data\OrderPaymentInterface;
use Magento\Vault\Api\Data\PaymentTokenInterface;
use Magento\Vault\Api\PaymentTokenManagementInterface;
use Magento\Vault\Api\PaymentTokenRepositoryInterface;
use Magento\Vault\Model\PaymentTokenFactory;
use Magento\Vault\Model\ResourceModel\PaymentToken as PaymentTokenResourceModel;

/**
 * Vault payment token repository
 */
class PaymentTokenManagement implements PaymentTokenManagementInterface
{
    /**
     * @var PaymentTokenRepositoryInterface
     */
    private $paymentTokenRepository;

    /**
     * @var PaymentTokenResourceModel
     */
    private $paymentTokenResourceModel;

    /**
     * @var PaymentTokenFactory
     */
    private $paymentTokenFactory;

    /**
     * @var \Magento\Framework\Api\FilterBuilder
     */
    private $filterBuilder;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    /**
     * @var EncryptorInterface
     */
    private $encryptor;

    /**
     * @param PaymentTokenRepositoryInterface $repository
     * @param PaymentTokenResourceModel       $paymentTokenResourceModel
     * @param PaymentTokenFactory             $paymentTokenFactory
     * @param FilterBuilder                   $filterBuilder
     * @param SearchCriteriaBuilder           $searchCriteriaBuilder
     * @param EncryptorInterface              $encryptor
     */
    public function __construct(
        PaymentTokenRepositoryInterface $repository,
        PaymentTokenResourceModel $paymentTokenResourceModel,
        PaymentTokenFactory $paymentTokenFactory,
        FilterBuilder $filterBuilder,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        EncryptorInterface $encryptor
    ) {
        $this->paymentTokenRepository    = $repository;
        $this->paymentTokenResourceModel = $paymentTokenResourceModel;
        $this->paymentTokenFactory       = $paymentTokenFactory;
        $this->filterBuilder             = $filterBuilder;
        $this->searchCriteriaBuilder     = $searchCriteriaBuilder;
        $this->encryptor                 = $encryptor;
    }

    /**
     * Lists payment tokens that match specified search criteria.
     *
     * @param int $customerId Customer ID.
     *
     * @return \Magento\Vault\Api\Data\PaymentTokenSearchResultsInterface Payment tokens search result interface.
     */
    public function getListByCustomerId($customerId)
    {
        $filters[] = $this->filterBuilder
            ->setField(PaymentTokenInterface::CUSTOMER_ID)
            ->setValue($customerId)
            ->create();
        $entities  = $this->paymentTokenRepository->getList(
            $this->searchCriteriaBuilder
                ->addFilters($filters)
                ->create()
        );

        return $entities;
    }

    /**
     * Get payment token by token Id.
     *
     * @param int $paymentId The payment token ID.
     *
     * @return PaymentTokenInterface|null Payment token interface.
     */
    public function getByPaymentId($paymentId)
    {
        $tokenData  = $this->paymentTokenResourceModel->getByOrderPaymentId($paymentId);
        $tokenModel = !empty($tokenData) ? $this->paymentTokenFactory->create(['data' => $tokenData]) : null;

        return $tokenModel;
    }

    /**
     * Get payment token by gateway token.
     *
     * @param string $token      The gateway token.
     * @param string $paymentMethodCode
     * @param int    $customerId Customer ID.
     *
     * @return PaymentTokenInterface|null Payment token interface.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByGatewayToken($token, $paymentMethodCode, $customerId)
    {
        $tokenData  = $this->paymentTokenResourceModel->getByGatewayToken($token, $paymentMethodCode, $customerId);
        $tokenModel = !empty($tokenData) ? $this->paymentTokenFactory->create(['data' => $tokenData]) : null;

        return $tokenModel;
    }

    /**
     * Get payment token by public hash.
     *
     * @param string $hash       Public hash.
     * @param int    $customerId Customer ID.
     *
     * @return PaymentTokenInterface|null Payment token interface.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByPublicHash($hash, $customerId)
    {
        $tokenData  = $this->paymentTokenResourceModel->getByPublicHash($hash, $customerId);
        $tokenModel = !empty($tokenData) ? $this->paymentTokenFactory->create(['data' => $tokenData]) : null;

        return $tokenModel;
    }

    /**
     * @param PaymentTokenInterface $token
     * @param OrderPaymentInterface $payment
     *
     * @return bool
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function saveTokenWithPaymentLink(PaymentTokenInterface $token, OrderPaymentInterface $payment)
    {
        $tokenDuplicate = $this->getByPublicHash(
            $token->getPublicHash(),
            $token->getCustomerId()
        );

        if (!empty($tokenDuplicate)) {
            if ($token->getIsVisible() || $tokenDuplicate->getIsVisible()) {
                $token->setEntityId($tokenDuplicate->getEntityId());
                $token->setIsVisible(true);
            } else if ($token->getIsVisible() === $tokenDuplicate->getIsVisible()) {
                $token->setEntityId($tokenDuplicate->getEntityId());
            } else {
                $token->setPublicHash(
                    $this->encryptor->getHash(
                        $token->getPublicHash() . $token->getGatewayToken()
                    )
                );
            }
        }

        $this->paymentTokenRepository->save($token);

        $result = $this->addLinkToOrderPayment($token->getEntityId(), $payment->getEntityId());

        return $result;
    }

    /**
     * Add link between payment token and order payment.
     *
     * @param int $paymentTokenId Payment token ID.
     * @param int $orderPaymentId Order payment ID.
     *
     * @return bool
     */
    public function addLinkToOrderPayment($paymentTokenId, $orderPaymentId)
    {
        return $this->paymentTokenResourceModel->addLinkToOrderPayment($paymentTokenId, $orderPaymentId);
    }
}
