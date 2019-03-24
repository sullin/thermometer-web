<?php

include "config.php";

$now = time();

function file_get_last_record($df) {
	$f = file($df);
	if (count($f) < 2) return FALSE;
	$d = explode(",", trim($f[count($f)-1]));
	if (count($d) < 2) return FALSE;
	return $d;
}

function find_cur_datafile() {
	// get last session data file from index
	$last = file_get_last_record($GLOBALS['indexfile']);
	if ($last === FALSE) return FALSE;
	$df = $GLOBALS['datadir'].$last[1];

	// check creation of last data file timestamp
	$date = strtotime($last[0]);
	if ($date === FALSE) return FALSE;
	$diff = $GLOBALS['now'] - $date;
	if ($diff > 0 && $diff < $GLOBALS['session_gap_max_s']) return $df;

	// check last record timestamp from last data file
	$last = file_get_last_record($df);
	if ($last === FALSE) return FALSE;

	$date = strtotime($last[0]);
	if ($date === FALSE) return FALSE;
	$diff = $GLOBALS['now'] - $date;
	if ($diff < 0 || $diff > $GLOBALS['session_gap_max_s']) return FALSE;

	return $df;
}

function create_new_datafile() {
	$fn = date("Y-m-d H:i", $GLOBALS['now']).".csv";
	if (file_exists($fn)) return $fn;

	$hdr = "time";
	foreach ($GLOBALS['col_map'] as $k=>$v) {
		$hdr .= ",".$k;
	}

	$df = fopen($GLOBALS['datadir'].$fn, "w+");
	if ($df === FALSE) return FALSE;
	fwrite($df, $hdr."\n");
	fclose($df);

	$create_idx = !file_exists($GLOBALS['indexfile']);
	$idx = fopen($GLOBALS['indexfile'], "a+");
	if ($df === FALSE) return FALSE;
	if ($create_idx) fwrite($idx, "time,filename\n");
	fwrite($idx, date("Y-m-d H:i", $GLOBALS['now']).",".$fn."\n");
	fclose($idx);
	return $GLOBALS['datadir'].$fn;
}

function store_data($a) {
	$fn = find_cur_datafile();
	if ($fn === FALSE) $fn = create_new_datafile();
	if ($fn === FALSE) return FALSE;

	$ln = date("Y-m-d H:i:s", $GLOBALS['now']);
	foreach($GLOBALS['col_map'] as $k => $v) {
		$ln .= ",";
		if (isset($a[$v])) {
			$ln .= floatval($_POST[$v]);
		}
	}

	$df = fopen($fn, "a");
	if ($df === FALSE) return FALSE;
	fwrite($df, $ln."\n");
	fclose($df);
	return TRUE;
}

function store_last($a) {
	$f = fopen($GLOBALS['datadir']."last_data.txt", "w+");
	if ($f === FALSE) return FALSE;
	foreach ($a as $k => $v) {
		fwrite($f, $k.':'.$v."\n");
	}
	fclose($f);
	return TRUE;
}

?>
