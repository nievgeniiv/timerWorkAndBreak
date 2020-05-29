<?php

Class Application
{
  public function run()
  {
    $controller = new ControllerTimer();
    $controller->run();
  }
}