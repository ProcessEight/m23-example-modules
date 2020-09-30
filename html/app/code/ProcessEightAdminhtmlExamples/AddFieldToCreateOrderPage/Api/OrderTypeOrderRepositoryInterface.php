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

namespace ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api;

/**
 * OrderTypeOrder CRUD interface.
 *
 */
interface OrderTypeOrderRepositoryInterface
{
    /**
     * Save OrderTypeOrder.
     *
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface $OrderTypeOrder
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\OrderTypeOrderInterface $OrderTypeOrder);

    /**
     * Retrieve OrderTypeOrder.
     *
     * @param int $OrderTypeOrderId
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById(int $OrderTypeOrderId);

    /**
     * Retrieve OrderTypeOrder by orderId.
     *
     * @param int $orderId
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByOrderId(int $orderId);

    /**
     * Retrieve OrderTypeOrders matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     *
     * @return \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete OrderTypeOrder.
     *
     * @param \ProcessEightAdminhtmlExamples\AddFieldToCreateOrderPage\Api\Data\OrderTypeOrderInterface $OrderTypeOrder
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\OrderTypeOrderInterface $OrderTypeOrder);

    /**
     * Delete OrderTypeOrder by ID.
     *
     * @param int $OrderTypeOrderId
     *
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById(int $OrderTypeOrderId);
}
