<?php

namespace Nxp\App\config;

use Nxp\Core\Common\Abstracts\Bootstrap\AbstractBootstrap;

/** 
 * This bootstrap class is for you to define any preferences you would like to load during bootstrap of the main framework. 
 * For example, you might want to check some database tables, check a connection, run a API request etc.
 * 
 * Please note however, this bootstrap class is ran on every page load, so please do not put resource intensive functions here.
 */

class bootstrap extends AbstractBootstrap
{
}