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

namespace ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class OrderTypes
 */
class OrderTypes implements OptionSourceInterface
{
    /**
     * @var \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeQuote
     */
    protected $orderTypeQuote;

    /**
     * Constructor
     *
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeQuote $orderTypeQuote
     */
    public function __construct(
        \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Model\OrderTypeQuote $orderTypeQuote
    ) {
        $this->orderTypeQuote = $orderTypeQuote;
    }

    /**
     * Get order type options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->orderTypeQuote->getOrderTypes();
        $options          = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }

        return $options;
    }
}
