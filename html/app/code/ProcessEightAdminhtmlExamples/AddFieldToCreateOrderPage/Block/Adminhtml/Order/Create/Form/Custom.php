<?php
/**
 * ProcessEightAdminhtmlExamples
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact ProcessEightAdminhtmlExamples for more information.
 *
 * @copyright   Copyright (c) 2020 ProcessEightAdminhtmlExamples
 * @author      ProcessEightAdminhtmlExamples
 *
 */

declare(strict_types=1);

namespace ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Block\Adminhtml\Order\Create\Form;

use Magento\Framework\Pricing\PriceCurrencyInterface;

/**
 * Class Custom
 *
 */
class Custom extends \Magento\Sales\Block\Adminhtml\Order\Create\AbstractCreate
{
    /**
     * @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\Source\OrderTypes
     */
    private $orderTypes;

    /**
     * @param \Magento\Backend\Block\Template\Context                                          $context
     * @param \Magento\Backend\Model\Session\Quote                                             $sessionQuote
     * @param \Magento\Sales\Model\AdminOrder\Create                                           $orderCreate
     * @param PriceCurrencyInterface                                                           $priceCurrency
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\Source\OrderTypes $orderTypes
     * @param array                                                                            $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Model\Session\Quote $sessionQuote,
        \Magento\Sales\Model\AdminOrder\Create $orderCreate,
        PriceCurrencyInterface $priceCurrency,
        \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\Source\OrderTypes $orderTypes,
        array $data = []
    ) {
        parent::__construct($context, $sessionQuote, $orderCreate, $priceCurrency, $data);
        $this->orderTypes = $orderTypes;
    }

    /**
     * Return Header CSS Class
     *
     * @return string
     */
    public function getHeaderCssClass() : string
    {
        return 'head-order-type';
    }

    /**
     * Return header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText() : \Magento\Framework\Phrase
    {
        return __('Order Type');
    }

    /**
     * Get order types for select
     *
     * @return array
     */
    public function getOrderTypes() : array
    {
        return $this->orderTypes->toOptionArray();
    }
}
