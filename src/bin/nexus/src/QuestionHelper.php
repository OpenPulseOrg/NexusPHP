<?php

class QuestionHelper
{
    /**
     * Prompt the user and wait for input.
     *
     * @param string $question The question to ask the user.
     * @return string The user's input.
     */
    public function ask($question)
    {
        $title = "\e[1m" . "\e[34m" . "Input: " . "\e[0m" . $question . "\n";
        echo $title;
        return trim(fgets(STDIN));
    }

    /**
     * Prompt the user with additional information and wait for input.
     *
     * @param string $question The question to ask the user.
     * @param string $info Additional information to display.
     * @return string The user's input.
     */
    public function askWithInfo($question, $info)
    {
        $title = "\e[1m" . "\e[34m" . "Input: " . "\e[0m" . $question . "\n";
        $additionalInfo = "\e[1m" . "\e[32m" . "Info: " . "\e[0m" . $info . "\n";

        echo $title . $additionalInfo;

        return trim(fgets(STDIN));
    }

    /**
     * Display colored text output.
     *
     * @param string $text The text to display.
     * @param string $color The color of the text.
     */
    public function output($text, $color = 'default')
    {
        $colors = [
            'default' => "\e[39m",
            'black' => "\e[30m",
            'red' => "\e[31m",
            'green' => "\e[32m",
            'yellow' => "\e[33m",
            'blue' => "\e[34m",
            'magenta' => "\e[35m",
            'cyan' => "\e[36m",
            'light_gray' => "\e[37m",
            'dark_gray' => "\e[90m",
            'light_red' => "\e[91m",
            'light_green' => "\e[92m",
            'light_yellow' => "\e[93m",
            'light_blue' => "\e[94m",
            'light_magenta' => "\e[95m",
            'light_cyan' => "\e[96m",
            'white' => "\e[97m",
        ];

        if (isset($colors[$color])) {
            echo $colors[$color] . $text . "\e[0m";
        } else {
            echo $text;
        }
    }
}
