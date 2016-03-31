<?php
class CsvGuestbookTest extends PHPUnit_Extensions_Database_TestCase
{
    protected function getDataSet()
    {
        $dataSet = new PHPUnit_Extensions_Database_DataSet_CsvDataSet();
        $dataSet->addTable('guestbook', dirname(__FILE__)."/guestbook.csv");
        return $dataSet;
    }
}
?>