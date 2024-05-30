<?php
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
********************************************************************************/
ini_set("include_path", "../");

require('send_mail.php');
require_once('config.php');
require_once('include/utils/utils.php');

// Email Setup
global $adb;

$cuentaid = $_GET['cuentaid'];
// $cuentaid = 205;

$query ="SELECT vtiger_cuentascobro.*, vtiger_cuentascobrocf.*, vtiger_account.*, vtiger_accountscf.*
FROM
vtiger_cuentascobro
INNER JOIN vtiger_crmentity ON vtiger_cuentascobro.cuentascobroid = vtiger_crmentity.crmid AND vtiger_crmentity.deleted = 0
INNER JOIN vtiger_cuentascobrocf ON vtiger_cuentascobro.cuentascobroid = vtiger_cuentascobrocf.cuentascobroid
INNER JOIN vtiger_account ON vtiger_account.accountid = vtiger_cuentascobrocf.cf_873
INNER JOIN vtiger_accountscf ON vtiger_accountscf.accountid = vtiger_account.accountid
WHERE
vtiger_cuentascobro.cuentascobroid =  '".$cuentaid."'";

$result0 = $adb->pquery($query, array());
$noofrows0 = $adb->num_rows($result0);

$cliente = $adb->query_result($result0,0,"accountname");
$cliente = decode_html($cliente);
$nit = $adb->query_result($result0,0,"siccode");
$direccion = $adb->query_result($result0,0,"cf_851");
$direccion = decode_html($direccion);
$ciudad = $adb->query_result($result0,0,"cf_853");
$ciudad = decode_html($ciudad);
$direccion = $direccion.' - '.$ciudad;
$nocuenta = $adb->query_result($result0,0,"cf_867");
$fecha = $adb->query_result($result0,0,"cf_865");
$fecha = date('d-m-Y', strtotime($fecha));
$concepto = $adb->query_result($result0,0,"cf_869");
$concepto = nl2br($concepto);
$valor = $adb->query_result($result0,0,"cf_871");
$valor = number_format($valor, 0, '.', ',');


$html ='
<html>
<head>
    <title></title>
</head>
<style>
.round_tablest {                   
  border-collapse: separate;
  border-spacing: 1;
  border: 1px solid #C3C4C4;
  border-radius: 15px;
  -moz-border-radius: 20px;
  padding: 1px;
  -webkit-border-radius: 5px;
  font-size: 1.1em;
  text-align: center;
}

table td input{
  width: 100%;
  padding: 3px;
  border: 1px solid #CCC;
  text-align: center;
}
body { 
  font-family: Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;
}
</style>
<body>
<div id="qrimprimir">
<br />
&nbsp;
<table align="center" border="1" cellpadding="5" cellspacing="0" style="width:80%;">
    <tbody>
        <tr>
            <td rowspan="2" style="text-align: center; width: 70%;">'.$cliente.' NIT '.$nit.'<br />
            '.$direccion.'</td>
            <td style="text-align: center;"><strong>CUENTA DE COBRO</strong></td>
        </tr>
        <tr>
            <td style="text-align: center;"><strong>No. '.$nocuenta.'</strong></td>
        </tr>
    </tbody>
</table>
&nbsp;

<table align="center" border="1" cellpadding="5" cellspacing="0" style="width:80%;">
    <tbody>
        <tr>
            <td colspan="3"><strong>Fecha:</strong> '.$fecha.'</td>
        </tr>
        <tr>
            <td style="width: 50%;"><strong>Apellidos y nombres y/o raz&oacute;n social:</strong><br />
            Sarah Milena Herrera Montoya</td>
            <td style="width: 20%;"><strong>R&eacute;gimen:</strong> Simplificado</td>
            <td style="width: 30%;"><strong>Documento identidad:</strong> 1.000.087.535</td>
        </tr>
        <tr>
            <td><strong>Direcci&oacute;n:</strong>&nbsp;Cll 39 52 40</td>
            <td><strong>Tel&eacute;fono:</strong> 3014584149</td>
            <td><strong>Ciudad:</strong> Bello - Antioquia</td>
        </tr>
    </tbody>
</table>
&nbsp;

<table align="center" border="1" cellpadding="5" cellspacing="0" style="width:80%;">
    <tbody>
        <tr>
            <td style="text-align: center; width: 70%;"><strong>Por concepto de:</strong></td>
            <td style="text-align: center;"><strong>Valor</strong></td>
        </tr>
        <tr>
            <td style="width: 70%; height: 100px; vertical-align: top; text-align: justify;">'.$concepto.'</td>
            <td style="text-align: center; vertical-align: top;">'.$valor.'</td>
        </tr>
        <tr>
            <td style="text-align: center; width: 70%;"><strong>Nota:</strong>&nbsp;Cuenta de ahorros Bancolombia No 257-354606-52</td>
            <td style="text-align: center;"><strong>Total: '.$valor.'</strong></td>
        </tr>
    </tbody>
</table>
&nbsp;

<table align="center" border="1" cellpadding="5" cellspacing="0" style="width:80%;">
    <tbody>
        <tr>
            <td>
            <strong>Atentamente:</strong>
            <br>&nbsp;<img src="firma.jpg" width="300px" height="60px">
            <div></div>
            C.C. 1.035.850.977</td>
        </tr>
        <tr>
            <td style="text-align: center;">Condición para aplicación de retención en la fuente
De acuerdo con el Decreto 2231 de 2023 el cual modificó los artículos 1.2.4.1.6 y 1.2.4.1.17 del DUT 1625 de 2016 manifiesto bajo la gravedad del juramento que:
Al final del año gravable 2024  SI___ NO_X_ imputaré costos y gastos a mis rentas de trabajo.</td>
        </tr>
    </tbody>
</table>
</div>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<!-- JQuery -->
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.0.0-rc.1/dist/html2canvas.min.js"></script>
<script type="text/javascript">
$( window ).on( "load", function() {
var contenido = document.getElementById("qrimprimir").innerHTML;
  var ventanaImpresion = window.open("", "", "height=1000,width=1000");
  ventanaImpresion.document.write("<html><head><title>Imprimir</title></head><body>");
  ventanaImpresion.document.write(contenido);
  ventanaImpresion.document.write("</body></html>");
  ventanaImpresion.document.close();
  ventanaImpresion.print();
});
</script>
</body>
</html>';

echo $html;

?>




