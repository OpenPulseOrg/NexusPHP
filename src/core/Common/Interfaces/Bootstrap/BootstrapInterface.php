<?php

namespace Nxp\Core\Common\Interfaces\Bootstrap;

interface BootstrapInterface
{
    public function preInit();
    public function postInit();

    public function preLoadServices();
    public function postLoadServices();

    public function preLoadPlugins();
    public function postLoadPlugins();

    public function preLoadConfigs();
    public function postLoadConfigs();

    public function preRoute();
    public function postRoute();

    public function preSessionStart();
    public function postSessionStart();

    public function preSystemChecks();
    public function postSystemChecks();

    public function preCleanHeaders();
    public function postCleanHeaders();

    public function preSetPreferences();
    public function postSetPreferences();

    public function preTrackPage();
    public function postTrackPage();
}
