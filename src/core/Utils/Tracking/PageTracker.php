<?php

namespace Nxp\Core\Utils\Tracking;

use Nxp\Core\Utils\Session\Manager;

/**
 * @return PageTracker
 */
class PageTracker
{
    private $current_page = '';
    private $previous_page = '';
    private $session;

    public function __construct()
    {
        $this->session = Manager::getInstance();
    }

    public function track()
    {
        if ($this->session->get('current_page')) {
            $this->previous_page = $this->session->get('current_page');
        }
        $this->current_page = $_SERVER['REQUEST_URI'];

        $this->session->set('current_page', $this->current_page);
        $this->session->set('previous_page', $this->previous_page);
    }

    public function getCurrentPage()
    {
        $this->current_page = $this->session->get('current_page');
        return $this->current_page;
    }

    public function getPreviousPage()
    {
        $this->previous_page = $this->session->get('previous_page');
        return $this->previous_page;
    }

    public function clear()
    {
        $this->current_page = '';
        $this->previous_page = '';
        $this->session->delete('current_page');
        $this->session->delete('previous_page');
    }

    public function getPageName()
    {
        $directoryURI = $_SERVER['REQUEST_URI'];
        $path = parse_url($directoryURI, PHP_URL_PATH);
        $components = explode('/', $path);

        return $components[1];
    }
}
