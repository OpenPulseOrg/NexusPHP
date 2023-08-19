<?php

// TO-DO
namespace Nxp\Core\Utils\Data;

/**
 * Pagination class for handling pagination logic.
 *
 * @package Nxp\Core\Utils\Data
 */
class Pagination
{
    private $totalItems;
    private $itemsPerPage;
    private $currentPage;

    public function __construct($totalItems, $itemsPerPage, $currentPage)
    {
        $this->totalItems = $totalItems;
        $this->itemsPerPage = $itemsPerPage;
        $this->currentPage = $currentPage;
    }

    public function getTotalPages()
    {
        return ceil($this->totalItems / $this->itemsPerPage);
    }

    public function getOffset()
    {
        return ($this->currentPage - 1) * $this->itemsPerPage;
    }

    public function generatePageLinks($baseUrl)
    {
        $totalPages = $this->getTotalPages();
        $pageLinks = [];

        // Generate links for the previous and next pages
        if ($this->currentPage > 1) {
            $pageLinks[] = '<a href="' . $baseUrl . '?page=' . ($this->currentPage - 1) . '">Previous</a>';
        }

        if ($this->currentPage < $totalPages) {
            $pageLinks[] = '<a href="' . $baseUrl . '?page=' . ($this->currentPage + 1) . '">Next</a>';
        }

        // Generate links for the page numbers
        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i == $this->currentPage) {
                $pageLinks[] = '<span>' . $i . '</span>';
            } else {
                $pageLinks[] = '<a href="' . $baseUrl . '?page=' . $i . '">' . $i . '</a>';
            }
        }

        return implode(' | ', $pageLinks);
    }
}
