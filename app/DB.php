<?php

class DB
{
  private $link;
  private $mysql;
  private static $instance;

  public static function getInstance() : DB
  {
    if (self::$instance === null) {
      self::$instance = new DB();
    }
    return self::$instance;
  }

  public function __construct()
  {
    $this->link = null;
  }

  public function connect()
  {
    if ($this->link === NULL) {
      $this->link = @ mysqli_connect(host_DB, user_DB, passwd_DB, name_DB);
      if (mysqli_connect_error()) {
        $str = __METHOD__ . ' Ошибка подключения к базе данных. HOST=' . host_DB .
          ', USER=' . user_DB. ', PASS=' . passwd_DB . ', DB=' . name_DB;
        writeFile($str);
        exit('Ошибка соединения с MySQL! Обратитесь к администратору!');
      }
    }
    return $this->link;
  }

  private function __clone() { }

  /**
   * @param string $sql
   * @param mixed ...$param
   * @return array
   */
  public function getRows(string $sql, ...$param) : array
  {
    $nof = substr_count($sql ,'?');
    if (count($param) === $nof){
      $sql = $this->prepare($sql, $param);
      $res = mysqli_query($this->mysql, $sql);
      if ($res === false) {
        $this->writeLog(__METHOD__ . ' ' . $sql, $param, true);
        return [];
      }
      while ($k = mysqli_fetch_assoc($res)) {
        $row[] = $k;
      }
      return $row ?? [];
    } else {
      $this->writeLog(__METHOD__ . ' ' . $sql, $param);
      return [];
    }
  }

  /**
   * @param string $sql
   * @param mixed ...$param
   * @return array
   */
  public function getRow(string $sql, ...$param) : array
  {
    $nof = substr_count($sql ,'?');
    if (count($param) === $nof){
      $sql = $this->prepare($sql, $param);
      $res = mysqli_query($this->mysql, $sql);
      if ($res === false) {
        $this->writeLog(__METHOD__ . ' ' . $sql, $param, true);
        return [];
      }
      $row = mysqli_fetch_assoc($res);
      return $row ?? [];
    } else {
      $this->writeLog(__METHOD__ . ' ' . $sql, $param);
      return [];
    }
  }

  /**
   * @param string $sql
   * @param mixed ...$param
   * @return array
   */
  public function getCell(string $sql, ...$param) : array
  {
    $nof = substr_count($sql ,'?');
    if (count($param) === $nof){
      $sql = $this->prepare($sql, $param);
      $res = mysqli_query($this->mysql, $sql);
      if ($res === false) {
        $this->writeLog(__METHOD__ . ' ' . $sql, $param, true);
        return [];
      }
      $value = mysqli_fetch_assoc($res);
      return $value ?? [];
    } else {
      $this->writeLog(__METHOD__ . ' ' . $sql, $param);
      return [];
    }
  }

  /**
   * @param string $sql
   * @param mixed ...$param
   */
  public function setData(string $sql, ...$param)
  {
    $nof = substr_count($sql ,'?');
    if (count($param) === $nof){
      $sql = $this->prepare($sql, $param);
      $res = mysqli_query($this->mysql, $sql);
      if ($res === false) {
        $this->writeLog(__METHOD__ . ' ' . $sql, $param, true);
        return;
      }
      return;
    } else {
      $this->writeLog(__METHOD__ . ' ' . $sql, $param);
      return;
    }
  }

  /**
   * @param string $sql
   * @param array $param
   * @return string
   */
  public function prepare(string $sql, array $param) : string
  {
    $this->mysql = $this->connect();
    $str = explode('?', $sql);
    $i = 1;
    $sql = $str[0];
    foreach ($param as $value) {
      if (gettype($value) === 'string' or gettype($value) === 'date') {
        $value = '"' . $this->mysql->real_escape_string($value) . '"';
      } else {
        $value = $this->mysql->real_escape_string($value);
      }
      $sql .= $value . $str[$i];
      $i++;
    }
    return $sql;
  }

  /**
   * @param string $sql
   * @param array $param
   * @param bool $ok
   */
  private function writeLog(string $sql, array $param, bool $ok = false)
  {
    if ($ok === true) {
      $str = 'Запращиваемые данные не существуют.';
    } else {
      $str = 'Количество данных не соответсвует количеству входных параметров.';
    }
    $str .= ' Запрос: ' . $sql . '  Параметры:';
    foreach ($param as $item) {
      $str .= $item .', ';
    }
    writeFile($str);
  }
}
