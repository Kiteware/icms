<?php
namespace Nixhatter\ICMS;

/**
 * ICMS - Intelligent Content Management System
 *
 * @package  Icms
 * @author   Dillon Aykac
 *
 * @copyright  Copyright (C) 2016 NiXX. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_ICMS') or die;

require_once 'init.php';

class app {
    public function execute() {

        $klein = new \Klein\Klein();

        // URL Format /admin/controller/action/argument
        // Example:   /admin/blog/create

        // Installer has not been run yet, force redirect to /install
        if (!file_exists('core/configuration.php') ) {
            $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

            if(($temp = strlen($url) - 7) >= 0 && strpos($url, 'install', $temp)) {
                include_once ('install.php');
            }

            header('Location: /install');

        } else {

            try {
                $parser = new \IniParser('core/configuration.php');
                if ($parser->parse()['production']['debug']) {
                    error_reporting(-1);
                    ini_set('display_errors', 'On');
                }
            } catch (\InvalidArgumentException $e) {
                exit('Error parsing configuration.php');
            }

            /**
             * RSS Feed
             */
            $klein->respond('/rss.xml', function () {
                $controller = new FrontController('blog', 'blog', 'rss');
                $controller->output();
            });

            /**
             *
             */
            $klein->respond('/[:model]?/[:controller]?/[:action]?/[:arg]?', function ($request) {
                /**
                 * Models store:
                 *  + Database interactions
                 *  + Commonly used functions
                 *  + Data getter/setters
                 */
                $model = $request->model;

                /**
                 * Controllers handle:
                 *  + User inputs
                 *  + User actions
                 *  + Store data for the views
                 */
                $controller = $request->controller;

                /**
                 * Action dictates which function in a controller is called
                 */
                $action = $request->action;

                /**
                 * If given, this variable is an argument sent to the action function
                 */
                $argument = $request->arg;

                if ($model === 'admin') {
                    $controller = new admin\AdminController($controller, $action, $argument);
                } else {
                    $controller = new FrontController($model, $controller, $action, $argument);
                }

                $controller->output();

            });

            /**
             * Default
             */
            $klein->respond('/', function () {
                $controller = new FrontController('user', 'home');
                $controller->output();
            });

            $klein->dispatch();

        }
    }

}