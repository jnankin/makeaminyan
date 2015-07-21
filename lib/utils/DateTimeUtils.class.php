<?

/* echo parseTime("9apm") . "\n";
  echo validTimeString("13pm") . "\n";
  echo validTimeString("13") . "\n";
  echo validTimeString("12") . "\n";
  echo validTimeString("0") . "\n";
  echo validTimeString("0am") . "\n";
  echo validTimeString("9:30pm") . "\n";
  echo "\n\n";
 */

class DateTimeUtils {

    public static function getWeekStartDate($wk_num, $yr, $first = 1, $format = 'n/d/Y') {
        $wk_ts = strtotime('+' . $wk_num . ' weeks', strtotime($yr . '0101'));
        $mon_ts = strtotime('-' . date('w', $wk_ts) + $first . ' days', $wk_ts);
        return date($format, $mon_ts);
    }

}
?>
