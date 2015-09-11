<?php
    include('connect-db.php');
    $result = mysql_query("SELECT * FROM aqn_master_table ORDER BY address ASC") or die(mysql_error());
    function xlsBOF()
    {
    echo pack("ssssss", 0x809, 0x8, 0x0, 0x10, 0x0, 0x0);
    return;
    }
    function xlsEOF()
    {
    echo pack("ss", 0x0A, 0x00);
    return;
    }
    function xlsWriteNumber($Row, $Col, $Value)
    {
    echo pack("sssss", 0x203, 14, $Row, $Col, 0x0);
    echo pack("d", $Value);
    return;
    }
    function xlsWriteLabel($Row, $Col, $Value )
    {
    $L = strlen($Value);
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x0, $L);
    echo $Value;
    return;
    }
    function xlsWriteHeader($Row, $Col, $Value )
    {
    $L = strlen($Value);
    echo pack("ssssss", 0x204, 8 + $L, $Row, $Col, 0x11, $L);
    echo $Value;
    return;
    }
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Content-Type: application/force-download");
    header("Content-Type: application/octet-stream");
    header("Content-Type: application/download");;
    header("Content-Disposition: attachment;filename=InventoryExport.xls");
    header("Content-Transfer-Encoding: binary ");
    xlsBOF();
     
    xlsWriteHeader(0,0,"IP Address");
    xlsWriteHeader(0,1,"Row Name");
    xlsWriteHeader(0,2,"Machine Name");
    xlsWriteHeader(0,3,"System Model");
    xlsWriteHeader(0,4,"Cab Location");
    xlsWriteHeader(0,5,"Unit Number");
    xlsWriteHeader(0,6,"Comments");
//    xlsWriteHeader(1,0,"");
//    xlsWriteHeader(1,1,"");
//    xlsWriteHeader(1,2,"");
//    xlsWriteHeader(1,3,"");
//    xlsWriteHeader(1,4,"");
//    xlsWriteHeader(1,5,"");
//    xlsWriteHeader(1,6,"");
//    xlsWriteHeader(1,7,"");
   $xlsRow = 2;
    while($row=mysql_fetch_array($result))
    {
    xlsWriteLabel($xlsRow,0,$row['address']);
    xlsWriteLabel($xlsRow,1,$row['row_name']);
    xlsWriteLabel($xlsRow,2,$row['hostname']);
    xlsWriteLabel($xlsRow,3,$row['model']);
    xlsWriteLabel($xlsRow,4,$row['cab']);
    xlsWriteLabel($xlsRow,5,$row['unit']);
    xlsWriteLabel($xlsRow,6,$row['comments']);
    $xlsRow++;
    }
    xlsEOF();

?>
