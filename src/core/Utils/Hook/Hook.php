<?php
namespace Nxp\Core\Utils\Hook;

class Hook {

    private static $actions = [];

    // Add a new action to a hook
    public static function addAction($hook_name, $callback, $priority = 10) {
        if (!isset(self::$actions[$hook_name])) {
            self::$actions[$hook_name] = [];
        }

        self::$actions[$hook_name][$priority][] = $callback;
    }

    // Execute actions attached to a hook
    public static function doAction($hook_name, ...$args) {
        if (isset(self::$actions[$hook_name])) {
            // Sort by priority
            ksort(self::$actions[$hook_name]);

            foreach (self::$actions[$hook_name] as $priority => $callbacks) {
                foreach ($callbacks as $callback) {
                    call_user_func_array($callback, $args);
                }
            }
        }
    }
}
