<?php

namespace Lof\ProductLabel\Api;

/**
 * @api
 */
interface LabelRepositoryInterface
{
    /**
     * Save
     *
     * @param \Lof\ProductLabel\Api\Data\LabelInterface $label
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function save(\Lof\ProductLabel\Api\Data\LabelInterface $label);

    /**
     * Get by id
     *
     * @param int $id
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * Delete
     *
     * @param \Lof\ProductLabel\Api\Data\LabelInterface $label
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function delete(\Lof\ProductLabel\Api\Data\LabelInterface $label);

    /**
     * Delete by id
     *
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     */
    public function deleteById($id);

    /**
     * Lists
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Framework\Api\SearchResultsInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Lists
     *
     * @return \Lof\ProductLabel\Api\Data\LabelInterface[] Array of items.
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getAll();

    /**
     * @return \Lof\ProductLabel\Api\Data\LabelInterface
     */
    public function getModelLabel();
}
