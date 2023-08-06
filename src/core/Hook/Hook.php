<?php
namespace Nxp\Core\Hook;

/**
 * The Hook class is used to manage and execute hooks/callbacks in the application.
 * Hooks allow developers to register callback functions that are executed at specific points
 * in the application's lifecycle. This enables extending and customizing the application
 * without directly modifying its core code.
 */
class Hook
{
    /**
     * @var array An associative array to store registered hooks and their corresponding callbacks.
     *            The structure of the array is as follows:
     *            [
     *                'hook_name' => [
     *                    'priority' => [callback1, callback2, ...],
     *                    'priority' => [callback3, callback4, ...],
     *                    ...
     *                ],
     *                ...
     *            ]
     */
    private static $hooks = array();

    /**
     * Register a hook callback function with an optional priority (default: 10).
     *
     * @param string   $hookName The name of the hook to which the callback is being registered.
     * @param callable $callback The callback function to be executed when the hook is triggered.
     * @param int      $priority Optional. The priority of the callback. Lower numbers indicate higher priority.
     *                           Callbacks with higher priority are executed before those with lower priority.
     *                           Default is 10.
     * @return void
     */
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

    /**
     * Execute the hook callbacks with optional arguments.
     *
     * This method triggers the registered callbacks associated with the specified hook.
     * The callbacks are executed in descending order of priority (higher priority first).
     *
     * @param string $hookName The name of the hook to execute.
     * @param array  $args     Optional. An array of arguments to pass to the hook callbacks.
     * @return mixed The final result of the hook execution. If any hook returns false,
     *               the execution is halted, and false is returned. Otherwise, the final result is returned.
     */
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

    /**
     * Execute all hooks with optional arguments.
     *
     * This method triggers all registered hooks along with their callbacks.
     * Hooks are executed in descending order of priority (higher priority first).
     *
     * @param array $args Optional. An array of arguments to pass to the hook callbacks.
     * @return mixed The final result of the hook execution. If any hook returns false,
     *               the execution is halted, and false is returned. Otherwise, the final result is returned.
     */
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

    /**
     * Remove a specific hook callback from the hook.
     *
     * @param string   $hookName The name of the hook from which the callback should be removed.
     * @param callable $callback The callback function to remove.
     * @param int      $priority Optional. The priority of the callback to remove. If not specified,
     *                           the callback will be removed from all priorities of the specified hook.
     *                           Default is 10.
     * @return void
     */
    public static function removeHook($hookName, $callback, $priority = 10)
    {
        if (isset(self::$hooks[$hookName][$priority])) {
            $index = array_search($callback, self::$hooks[$hookName][$priority]);
            if ($index !== false) {
                unset(self::$hooks[$hookName][$priority][$index]);
            }
        }
    }

    /**
     * Remove all hooks with a specific name and optional priority.
     *
     * @param string $hookName The name of the hook to remove.
     * @param int    $priority Optional. The priority of the hook to remove. If not specified,
     *                         all priorities of the specified hook will be removed.
     * @return void
     */
    public static function removeAllHooks($hookName, $priority = null)
    {
        if ($priority === null) {
            unset(self::$hooks[$hookName]);
        } else {
            unset(self::$hooks[$hookName][$priority]);
        }
    }
}
