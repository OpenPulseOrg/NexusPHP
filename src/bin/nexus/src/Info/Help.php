<?php

namespace Info;

class Help
{
    public function displayHelp($commands)
    {
        $titleColor = "\033[1;36m";  // Cyan color
        $commandColor = "\033[1;33m";  // Yellow color
        $resetColor = "\033[0m";  // Reset color

        $helpText = $titleColor . "Usage:" . $resetColor . " php nexus.php [command] [options]\n\n" .
            $titleColor . "Commands:" . $resetColor . "\n";

        // Dynamically generate commands based on $commands array
        foreach ($commands as $command => $details) {
            if (is_array($details) && array_key_exists('class', $details) && array_key_exists('method', $details)) {
                // Command without subcommands
                $helpText .= "  " . $commandColor . $command . $resetColor . "       " . $details['description'] . "\n";
            } else {
                // Command with subcommands
                foreach ($details as $subcommand => $subDetails) {
                    $helpText .= "  " . $commandColor . $command . " " . $subcommand . $resetColor . "       " . $subDetails['description'] . "\n";
                }
            }
        }

        // Other help text
        $helpText .= $titleColor . "Description:" . $resetColor . "\n" .
            "  This CLI application provides a set of commands for managing database migrations.\n\n" .
            $titleColor . "Examples:" . $resetColor . "\n";

        // Dynamically generate examples based on $commands array
        foreach ($commands as $command => $details) {
            if (is_array($details) && array_key_exists('class', $details) && array_key_exists('method', $details)) {
                // Command without subcommands
                $helpText .= "  php nexus.php " . $commandColor . $command . $resetColor . " - " . $details['description'] . "\n";
            } else {
                // Command with subcommands
                foreach ($details as $subcommand => $subDetails) {
                    $helpText .= "  php nexus.php " . $commandColor . $command . " " . $subcommand . $resetColor . " - " . $subDetails['description'] . "\n";
                }
            }
        }

        echo $helpText;
    }
}
