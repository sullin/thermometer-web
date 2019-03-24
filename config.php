<?php

// shared secret, random string, max 15char
$auth_token = "12345abcde";

// data directory name
$datadir = "data/";

// data directory index file name
$indexfile = $datadir."index.csv";

// max gap between samples to consider as single session
$session_gap_max_s = 60*60*5; // 5h

// sensor naming and mapping
$col_map = ["Zone1" => "w1_1234567890123456", "Zone2" => "w1_0987654321098765"];

?>
