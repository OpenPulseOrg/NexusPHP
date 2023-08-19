<?php

namespace Nxp\Core\Utils\Pagination;

use Nxp\Core\Database\Query;

class Paginator
{
    private $query;
    private $perPage;
    private $currentPage;
    private $totalItems;

    public function __construct(Query $query, $perPage = 15, $currentPage = 1)
    {
        $this->query = $query;
        $this->perPage = $perPage;
        $this->currentPage = $currentPage;
        $this->setTotalItems();
    }

    private function setTotalItems()
    {
        $this->totalItems = $this->query->count();
    }

    public function getResults()
    {
        $offset = ($this->currentPage - 1) * $this->perPage;
        $this->query->limit($this->perPage)->offset($offset);
        return $this->query->fetchAll();
    }

    public function totalItems()
    {
        return $this->totalItems;
    }

    public function totalPages()
    {
        return ceil($this->totalItems / $this->perPage);
    }

    public function currentPage()
    {
        return $this->currentPage;
    }

    public function previousPage()
    {
        return max(1, $this->currentPage - 1);
    }

    public function nextPage()
    {
        return min($this->totalPages(), $this->currentPage + 1);
    }
}
