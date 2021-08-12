<?php

namespace antonyz89\pagesize;

/**
 * Trait PageSizeTrait
 * @package antonyz89\pagesize
 *
 * @property PageSize $pageSize
 *
 * @property-read array|false $pagination
 */
trait PageSizeTrait
{
    /** @var string|null */
    public $pageSizeId = 'per-page';

    /** @var PageSize|null */
    private $_pageSize;

    /** @var array|false|null */
    private $_pagination;

    /**
     * @return array|false
     */
    public function getPagination()
    {
        if ($this->_pagination === null) {
            if (!$this->pageSize) {
                $this->_pagination = ['pageSize' => PageSize::$defaultPageSize];
            } else {
                $this->_pagination = $this->pageSize->pageSize === '0' ? false : ['pageSize' => $this->pageSize->pageSize];
            }
        }

        return $this->_pagination;
    }

    public function getPageSize(): PageSize
    {
        if ($this->_pageSize === null) {
            $this->_pageSize = new PageSize([
                'options' => [
                    'id' => $this->pageSizeId
                ]
            ]);
        }

        return $this->_pageSize;
    }
}
