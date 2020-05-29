<?php

spl_autoload_register(function ($classname) {
  $folder = __DIR__ . '/';

  if (strpos($classname, 'Controller') === 0) {
    $folder .= 'Controllers/';
  } elseif (strpos($classname, 'Model') === 0) {
    $folder .= 'Models/';
  }

  $filename = $folder . $classname . '.php';
  if (file_exists($filename)) {
    require_once $filename;
  }
}
);