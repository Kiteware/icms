<?php
/**
 * ICMS - Intelligent Content Management System
 *
 * @package  Icms
 * @author   Dillon Aykac
 *
 * @copyright  Copyright (C) 2016 NiXX. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
namespace Nixhatter\ICMS;

define('ICMS_MINIMUM_PHP', '5.3.10');

if (version_compare(PHP_VERSION, ICMS_MINIMUM_PHP, '<'))
{
    exit('Your PHP version (' . ICMS_MINIMUM_PHP . ') is too old for ICMS. Please install version 5.3 or higher');
}

/**
 * Where everything actually starts
 */
require_once "core/app.php";

$app = new app();

$app->execute();