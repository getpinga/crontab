<?php

namespace Pinga\Crontab;

class Parser
{
    public function parse(string $cronExpression, $startTime = null)
    {
        if ($startTime === null) {
            $startTime = time();
        }

        // Splitting the cron expression into its components
        list($minute, $hour, $dayOfMonth, $month, $dayOfWeek) = explode(' ', $cronExpression);

        // This function checks if a value is within a cron field
        $isWithinField = function ($value, $field) {
            if ($field == '*') {
                return true;
            }
            foreach (explode(',', $field) as $f) {
                if (strpos($f, '-') !== false) {
                    list($min, $max) = explode('-', $f);
                    if ($value >= $min && $value <= $max) {
                        return true;
                    }
                } elseif (strpos($f, '/') !== false) {
                    list($range, $step) = explode('/', $f);
                    if ($range == '*') {
                        $range = "0-59";
                    }
                    list($min, $max) = explode('-', $range);
                    for ($i = $min; $i <= $max; $i += $step) {
                        if ($value == $i) {
                            return true;
                        }
                    }
                } elseif ($value == $f) {
                    return true;
                }
            }
            return false;
        };

        $currentTime = strtotime(date('Y-m-d H:i:00', $startTime)); // Round down to the nearest minute

        while (true) {
            $min = intval(date('i', $currentTime));
            $hr = intval(date('H', $currentTime));
            $dom = intval(date('d', $currentTime));
            $mon = intval(date('m', $currentTime));
            $dow = intval(date('w', $currentTime));

            if ($isWithinField($min, $minute) &&
                $isWithinField($hr, $hour) &&
                $isWithinField($dom, $dayOfMonth) &&
                $isWithinField($mon, $month) &&
                $isWithinField($dow, $dayOfWeek)) {
                return [$currentTime]; // Changed line
            }

            $currentTime += 60; // Check the next minute
        }
    }
}
