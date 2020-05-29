<?php


class ModelTimer
{

  public static function create(int $timeWork)
  {
    $db = DB::getInstance();
    $sql = 'INSERT INTO work(date, day_of_week, time_work_start, all_time_work) VALUES(?, ?, ?, ?)';
    return $db->setData($sql, date('Y-m-d H:i:s'), date('l'), date('H:i'), (string)$timeWork);
  }

  public static function update(int $timeWork)
  {
    $db = DB::getInstance();
    $sql = 'SELECT MAX(id), all_time_work FROM work';
    $res = $db->getRow($sql);
    $id = $res['MAX(id)'];
    $time = (int)$res['all_time_work'];
    $time += $timeWork;
    $sql = 'UPDATE work SET all_time_work=? WHERE id=?';
    return $db->setData($sql, (string)$time, $id);
  }

  public static function end()
  {
    $db = DB::getInstance();
    $sql = 'SELECT MAX(id) FROM work';
    $id = $db->getCell($sql)['MAX(id)'];
    $sql = 'SELECT all_time_work FROM work WHERE id=?';
    $res = (int)$db->getCell($sql, $id)['all_time_work'];
    $H = (int)($res / 3600);
    //$m = (int)$res - $H * 60;
    $m = (int)($res / 60);
    $k = $H . ':' . $m;
    $sql = 'UPDATE work SET time_work_end=?, all_time_work=? WHERE id=?';
    return $db->setData($sql, date('H:i'), $k, $id);
  }
}