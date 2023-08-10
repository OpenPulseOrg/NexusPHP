<?php

namespace Nxp\Core\Utils\Error\Services;

use Sentry\Breadcrumb;
use Sentry\State\Scope;
use function Sentry\init;
use Nxp\Core\Config\ConfigurationManager;
use function Sentry\configureScope;
use function Sentry\captureException;

class SentryService
{
    private $useSentry;
    private $initialized = false;

    public function __construct()
    {
        $this->useSentry = ConfigurationManager::get("app", "USE_SENTRY");

        $dsn = ConfigurationManager::get("app", "SENTRY_DSN");
        if (empty($dsn)) {
            throw new \Exception('Sentry DSN is required but not set in the configuration.');
        }
        init(['dsn' => $dsn]);
        $this->initialized = true;
    }

    public function captureError($errorDetails, ?\Exception $e = null)
    {
        if (!$this->initialized) {
            return;
        }

        configureScope(function (Scope $scope) use ($errorDetails) {
            foreach ($errorDetails as $key => $value) {
                $scope->setExtra($key, $value);
            }
        });

        if ($e) {
            captureException($e);
        }
    }

    public function addUserContext($user)
    {
        if (!$this->initialized) {
            return;
        }

        configureScope(function (Scope $scope) use ($user) {
            $scope->setUser($user);
        });
    }

    public function addBreadcrumb($message, $category = 'info', $data = [])
    {
        if (!$this->initialized) {
            return;
        }

        $breadcrumb = new Breadcrumb($category, $message, '', 0, $data);
        \Sentry\addBreadcrumb($breadcrumb);
    }

    public function setTag($key, $value)
    {
        if (!$this->initialized) {
            return;
        }

        configureScope(function (Scope $scope) use ($key, $value) {
            $scope->setTag($key, $value);
        });
    }
}
