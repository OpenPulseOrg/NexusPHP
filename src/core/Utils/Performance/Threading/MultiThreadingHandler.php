<?php

// TO-DO
namespace Nxp\Core\Utils\Performance\Threading;

/**
 * MultiThreadingHandler class for executing threads simultaneously.
 *
 * @package Nxp\Core\Utils\Performance\Threading
 */
class MultiThreadingHandler
{
    private $threads;
    private $threadCount;
    private $results;

    /**
     * MultiThreadingHandler constructor.
     *
     * @param int $threadCount The maximum number of threads to be executed simultaneously.
     */
    public function __construct($threadCount = 1)
    {
        $this->threads = [];
        $this->threadCount = $threadCount;
        $this->results = [];
    }

    /**
     * Adds a thread to be executed.
     *
     * @param callable $thread The thread function or object with an __invoke() method.
     * @return void
     */
    public function addThread($thread)
    {
        $this->threads[] = $thread;
    }

    /**
     * Starts the execution of threads.
     *
     * @return void
     */
    public function startThreads()
    {
        $threadCount = count($this->threads);
        $threadCount = min($threadCount, $this->threadCount);

        for ($i = 0; $i < $threadCount; $i++) {
            $this->startThread($i);
        }

        $this->waitForThreads();
    }

    /**
     * Starts a thread by forking the current process.
     *
     * @param int $index The index of the thread to start.
     * @return void
     */
    private function startThread($index)
    {
        $pid = pcntl_fork();

        if ($pid == -1) {
            die('Could not fork');
        } elseif ($pid) {
            // Parent process
            return;
        } else {
            // Child process
            $result = $this->executeThread($index);
            $this->storeResult($index, $result);
            exit();
        }
    }

    /**
     * Waits for the child threads to finish their execution.
     *
     * @return void
     */
    private function waitForThreads()
    {
        $threadCount = count($this->threads);

        while ($threadCount > 0) {
            $status = null;
            $pid = pcntl_wait($status);
            $threadIndex = array_search($pid, $this->threads, true);

            if ($threadIndex !== false) {
                unset($this->threads[$threadIndex]);
                $threadCount--;
            }
        }
    }

    /**
     * Executes a thread.
     *
     * @param int $index The index of the thread to execute.
     * @return mixed The result of the executed thread.
     */
    private function executeThread($index)
    {
        $thread = $this->threads[$index];
        return $thread();
    }

    /**
     * Stores the result of an executed thread.
     *
     * @param int   $index  The index of the thread.
     * @param mixed $result The result of the thread.
     * @return void
     */
    private function storeResult($index, $result)
    {
        $this->results[$index] = $result;
    }

    /**
     * Retrieves the results of the executed threads.
     *
     * @return array An associative array where keys are thread indices and values are thread results.
     */
    public function getResults()
    {
        return $this->results;
    }
}
