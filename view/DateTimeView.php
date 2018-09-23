<?php

namespace view;

class DateTimeView
{

    public function show()
    {
        date_default_timezone_set("Europe/Stockholm");

        $weekday = date("l");
        $dayOfMonth = date("jS");
        $month = date("F");
        $year = date("Y");
        $timeOfDay = date("H:i:s");

        $timeString = $weekday . ", the " . $dayOfMonth . " of " . $month . " " . $year . ", The time is " . $timeOfDay;

        return "<p>" . $timeString . "</p>";
    }
}
