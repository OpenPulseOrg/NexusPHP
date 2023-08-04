<?php

// TO-DO
namespace Nxp\Core\Utils\Performance\Benchmarking;

/**
 * Benchmarking class for measuring execution time and memory usage of code segments.
 *
 * @package Nxp\Core\Utils\Performance\Benchmarking
 */
class Benchmarking
{
    private $startTimes = [];
    private $executionTimes = [];
    private $memoryUsages = [];

    /**
     * Starts a benchmark by recording the start time and memory usage.
     *
     * @param string $name The name of the benchmark.
     * @return void
     */
    public function startBenchmark($name)
    {
        $this->startTimes[$name] = microtime(true);
        $this->memoryUsages[$name] = memory_get_usage(true);
    }

    /**
     * Ends a benchmark by calculating the execution time and storing it.
     *
     * @param string $name The name of the benchmark.
     * @throws \Exception If the benchmark with the given name was not started.
     * @return void
     */
    public function endBenchmark($name)
    {
        if (isset($this->startTimes[$name])) {
            $executionTime = microtime(true) - $this->startTimes[$name];
            $this->executionTimes[$name] = $executionTime;
            unset($this->startTimes[$name]);
        } else {
            throw new \Exception("Benchmark with name '$name' was not started.");
        }
    }

    /**
     * Retrieves the execution time of a benchmark.
     *
     * @param string $name The name of the benchmark.
     * @throws \Exception If the benchmark with the given name was not completed or does not exist.
     * @return float The execution time in seconds.
     */
    public function getExecutionTime($name)
    {
        if (isset($this->executionTimes[$name])) {
            return $this->executionTimes[$name];
        } else {
            throw new \Exception("Benchmark with name '$name' was not completed or does not exist.");
        }
    }

    /**
     * Retrieves the execution times of all benchmarks.
     *
     * @return array An associative array where keys are benchmark names and values are execution times.
     */
    public function getAllExecutionTimes()
    {
        return $this->executionTimes;
    }

    /**
     * Retrieves the memory usage of a benchmark.
     *
     * @param string $name The name of the benchmark.
     * @throws \Exception If the benchmark with the given name was not started or does not exist.
     * @return int The memory usage in bytes.
     */
    public function getMemoryUsage($name)
    {
        if (isset($this->memoryUsages[$name])) {
            return $this->memoryUsages[$name];
        } else {
            throw new \Exception("Benchmark with name '$name' was not started or does not exist.");
        }
    }

    /**
     * Retrieves the memory usages of all benchmarks.
     *
     * @return array An associative array where keys are benchmark names and values are memory usages in bytes.
     */
    public function getAllMemoryUsages()
    {
        return $this->memoryUsages;
    }

    /**
     * Formats bytes to a human-readable format.
     *
     * @param int $bytes The number of bytes.
     * @return string The formatted bytes with unit.
     */
    public function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Retrieves the formatted memory usage of a benchmark.
     *
     * @param string $name The name of the benchmark.
     * @return string The formatted memory usage.
     */
    public function getFormattedMemoryUsage($name)
    {
        $memoryUsage = $this->getMemoryUsage($name);
        return $this->formatBytes($memoryUsage);
    }
}
