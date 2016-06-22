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
require_once "init.php";


class app
{
    private $g_model;
    private $g_controller;
    private $g_action;
    private $g_id;

    public function execute() {

        $klein = new \Klein\Klein();

        // URL Format /admin/controller/action
        // example:   /admin/blog/create

        if (!file_exists('core/configuration.php') ) {
            $klein->respond('/install',function ($request) {
                include_once ('install.php');
                die();
            });
            $klein->respond(function () {
                header("Location: /install");
                die();
            });
        } else {
            try {
                $parser = new \IniParser('core/configuration.php');
                if ($parser->parse()["production"]["debug"]) {
                    error_reporting(-1);
                    ini_set('display_errors', 'On');
                }
            } catch (\InvalidArgumentException $e) {
                // Could not load config file, die
                die("Could not load configuration file.");
            }
            $klein->respond('/rss.xml', function ($request) {
                $frontController = new FrontController(new Router, "blog", "blog", "rss");
                echo $frontController->output();
            });
            $klein->respond('/[:model]?/[:controller]?/[:action]?/[:id]?', function ($request) {
                $this->g_model = $request->model;
                $this->g_controller = $request->controller;
                $this->g_action = $request->action;
                $this->g_id = $request->id;

                if (strcmp($this->g_model, "admin") == 0) {
                    $frontController = new admin\AdminController(new Router, $this->g_controller, $this->g_action, $this->g_id);
                } elseif (strcmp($this->g_model, "blog") == 0) {
                    $frontController = new FrontController(new Router, $this->g_model, $this->g_model, $this->g_controller, $this->g_action);
                } else {
                    $frontController = new FrontController(new Router, $this->g_model, $this->g_controller, $this->g_action, $this->g_id);
                }
                echo $frontController->output();
            });
            $klein->respond('/', function ($request) {
                $g_model = "home";
                $g_controller = "home";
                $frontController = new FrontController(new Router, $g_model, $g_controller);
                echo $frontController->output();
            });

        }
        $klein->dispatch();
    }

}