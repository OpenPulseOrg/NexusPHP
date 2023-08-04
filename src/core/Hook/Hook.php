<?php

namespace Nxp\Core\Hook;

class Hook
{
    private static $hooks = array();

    // Register a hook callback function with optional priority (default: 10)
    public static function addHook($hookName, $callback, $priority = 10)
    {
        if (!isset(self::$hooks[$hookName])) {
            self::$hooks[$hookName] = array();
        }
        if (!isset(self::$hooks[$hookName][$priority])) {
            self::$hooks[$hookName][$priority] = array();
        }
        self::$hooks[$hookName][$priority][] = $callback;
    }

    // Execute the hook callbacks with optional arguments
    public static function executeHook($hookName, $args = array())
    {
        if (isset(self::$hooks[$hookName])) {
            $callbacks = self::$hooks[$hookName];

            // Sort callbacks by priority (higher priority first)
            krsort($callbacks);

            foreach ($callbacks as $callbackGroup) {
                foreach ($callbackGroup as $callback) {
                    if (is_callable($callback)) {
                        $args = call_user_func_array($callback, $args);
                        // Check if the hook returned a "halt" value (e.g., false)
                        if ($args === false) {
                            break 2; // Break both foreach loops
                        }
                    }
                }
            }
        }

        return $args;
    }

    // Execute all hooks with optional arguments
    public static function executeAllHooks($args = array())
    {
        $callbacks = self::$hooks;

        // Sort hooks by priority (higher priority first)
        foreach ($callbacks as &$hookCallbacks) {
            krsort($hookCallbacks);
        }

        foreach ($callbacks as $hookName => $callbackGroups) {
            foreach ($callbackGroups as $callbackGroup) {
                foreach ($callbackGroup as $callback) {
                    if (is_callable($callback)) {
                        $args = call_user_func_array($callback, $args);
                        // Check if the hook returned a "halt" value (e.g., false)
                        if ($args === false) {
                            return false;
                        }
                    }
                }
            }
        }

        return $args;
    }

    // Remove a specific hook callback from the hook
    public static function removeHook($hookName, $callback, $priority = 10)
    {
        if (isset(self::$hooks[$hookName][$priority])) {
            $index = array_search($callback, self::$hooks[$hookName][$priority]);
            if ($index !== false) {
                unset(self::$hooks[$hookName][$priority][$index]);
            }
        }
    }

    // Remove all hooks with a specific name and optional priority
    public static function removeAllHooks($hookName, $priority = null)
    {
        if ($priority === null) {
            unset(self::$hooks[$hookName]);
        } else {
            unset(self::$hooks[$hookName][$priority]);
        }
    }
}
