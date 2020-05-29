<?php

class ServiceExportExcel
{
  public static function createTable(array $data)
  {
    // Подключаем класс для работы с excel
    require_once('PHPExcel.php');
// Подключаем класс для вывода данных в формате excel
    require_once('PHPExcel/Writer/Excel5.php');

// Создаем объект класса PHPExcel
    $xls = new PHPExcel();
// Устанавливаем индекс активного листа
    $xls->setActiveSheetIndex(0);
// Получаем активный лист
    $sheet = $xls->getActiveSheet();
// Подписываем лист
    $sheet->setTitle('Время работы');

// Вставляем текст в ячейку A1
    $sheet->setCellValue("A1", 'Таблица умножения');
    $sheet->getStyle('A1')->getFill()->setFillType(
      PHPExcel_Style_Fill::FILL_SOLID);
    $sheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');

// Объединяем ячейки
    $sheet->mergeCells('A1:H1');

// Выравнивание текста
    $sheet->getStyle('A1')->getAlignment()->setHorizontal(
      PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    for ($i = 2; $i < 10; $i++) {
      for ($j = 2; $j < 10; $j++) {
        // Выводим таблицу умножения
        $sheet->setCellValueByColumnAndRow(
          $i - 2,
          $j,
          $i . "x" .$j . "=" . ($i*$j));
        // Применяем выравнивание
        $sheet->getStyleByColumnAndRow($i - 2, $j)->getAlignment()->
        setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
      }
    }
  }

  public static function getTable()
  {
    // Выводим HTTP-заголовки
    header ( "Expires: Mon, 1 Apr 1974 05:00:00 GMT" );
    header ( "Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT" );
    header ( "Cache-Control: no-cache, must-revalidate" );
    header ( "Pragma: no-cache" );
    header ( "Content-type: application/vnd.ms-excel" );
    header ( "Content-Disposition: attachment; filename=matrix.xls" );

// Выводим содержимое файла
    $objWriter = new PHPExcel_Writer_Excel5($xls);
    $objWriter->save('php://output');
  }
}