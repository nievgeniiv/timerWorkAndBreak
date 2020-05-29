<?php

function bashCMD(string $trigger)
{
  $cmd = 'mpg123 /home/zhenya/Документы/php/Scripts/TimerWork/public/files/Sound.mp3';
  shell_exec($cmd);
  $cmd = 'wmctrl -k '.$trigger;
  shell_exec($cmd);
}

function writeFile($str)
{
  $fd = fopen(LOG . "log.txt", 'a') or die("не удалось создать файл");
  $data = date('d.M.Y H:i:s') . ' ' . $str . "\n";
  fwrite($fd, $data);
  fclose($fd);
}
