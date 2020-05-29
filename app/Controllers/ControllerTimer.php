<?php

class ControllerTimer
{
  private $timeWork = null;
  private $timePause = null;
  private $out = null;
  private $response = null;
  private $exportExel = null;
  private $createUpdate = 'c';

  public function run()
  {
    while (!$this->exportExel) {
      echo 'Экспортировать данные из БД в Exel? [y/n]';
      $this->exportExel = trim(fgets(STDIN));
    }
    $this->isY($this->exportExel);
    while (!$this->out) {
      if ($this->timeWork and $this->timePause) {
        $this->response = null;
        while (!$this->response) {
          echo 'Включить заново таймер с такими же параметрами? [y/n/q]';
          $this->response = trim(fgets(STDIN));
        }
        switch ($this->response) {
          case 'q':
            $this->exit();
          case 'n':
            $this->timeWork = null;
            $this->timePause = null;
            $this->setData();
            break;
        }
      }
      $this->setData();
      $this->onOf();
      $this->createUpdate = 'u';
    }
  }

  public function setData()
  {
    while (!$this->timeWork) {
      echo 'Введите время работы в минутах: ';
      $k = trim(fgets(STDIN));
      $this->timeWork = $k * 60;
    }
    while (!$this->timePause) {
      echo 'Введите время отдыха: ';
      $k = trim(fgets(STDIN));
      $this->timePause = $k * 60;
    }
  }

  public function onOf()
  {
    if ($this->createUpdate === 'c') {
      ModelTimer::create($this->timeWork);
    } elseif ($this->createUpdate === 'u') {
      ModelTimer::update($this->timeWork);
    }
    sleep($this->timeWork);
    bashCMD('on');
    sleep($this->timePause);
    bashCMD('off');
  }

  private function isY(string $response)
  {
    if ($response === 'y') {
      $this->export();
    }
    $this->exportExel = null;
  }

  private function export()
  {

  }

  private function exit()
  {
    ModelTimer::end();
    while (!$this->exportExel) {
      echo 'Экспортировать данные из БД в Exel? [y/n]';
      $this->exportExel = trim(fgets(STDIN));
    }
    $this->isY($this->exportExel);
    exit;
  }
}