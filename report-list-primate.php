<?php
	require("./inc/config-system.inc.php");
	require("./inc/config-db.inc.php");
	require("./inc/function.inc.php");
	require("./libraries/adodb/adodb.inc.php");

	require("./xcrud/xcrud_config.php");
	require("./xcrud/xcrud_db.php");
	require("./xcrud/functions.php");

	// connection database
	$objConn = ADONewConnection(DB_DRIVER);
	$objConn->Connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
	//$objConn->debug = TRUE;
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>บัญชีรายนามพระภิกษุสามเณร</title>

<style>
@media all
{
	@page {
	  size: A4;
	  margin: 2.5mm 1.5mm 0mm 1.5mm;
	}
	.page-break{ page-break-after: always;}
	.body {
	font-size: 11pt;
	color: #000;
	position: relative;
	padding: 0;
	outline: 0;
	border: 0;
	size: A4;
	/*margin: 0mm;*/
		width: 21cm;
		height: 29.7cm;
		margin: 0mm auto;
		padding:0;
		position:relative;
		display: table;
	}
	table, td, th {
		border: 1px solid #000;
		text-align: left;
	}

	table {
		border-collapse: collapse;
		width: 100%;
	}

	th, td {
		margin:0;
	}
	.center{ text-align:center}
	.margintop{ margin-top:20px}
	.width-5{
		/*width:3%;*/
		vertical-align: middle;
		padding-top: 15px;
		text-align: center;
	}
	.width-10{
		/*width:10%;*/
		padding-left: 10px;
		padding-top: 15px;

	}
	.width-12{
		/*width:12%;*/
		padding-left: 10px;
		padding-top: 15px;
	}
	.width-15{
		/*width:15%;*/
		padding-left: 10px;
		padding-top: 15px;
	}
	.width-25{
		/*width:25%;*/
		padding-left: 10px;
		padding-top: 15px;
	}
	.width-30{
		/*width:30%;*/
		padding-left: 10px;
		padding-top: 15px;
	}
	.width-35{
		/*width:35%;*/
		padding-left: 10px;
		padding-top: 15px;
	}
	.small{font-size: 9pt;}
	.h1 {
		color: #000;
		background-color: #FFF;
		display: block;
		padding: 10px;
		text-align:center;
		margin:0;
		font-size: 18pt;
	}
	.h2 {
		color: #000000;
		display: block;
		padding: 10px;
		text-align: center;
		font-size: 12pt;
		margin-top: 10px;
		margin-right: 0;
		margin-bottom: 10px;
		margin-left: 0;
	}

	@font-face {
		font-family: 'THK2DJuly8';
		src: url('css/print_report/th_k2d_july8_bold_italic-webfont.eot');
		src: url('css/print_report/th_k2d_july8_bold_italic-webfont.eot?#iefix') format('embedded-opentype'),
			 url('css/print_report/th_k2d_july8_bold_italic-webfont.woff') format('woff'),
			 url('css/print_report/th_k2d_july8_bold_italic-webfont.ttf') format('truetype');
		font-weight: bold;
		font-style: italic;

	}

	@font-face {
		font-family: 'THK2DJuly8';
		src: url('css/print_report/th_k2d_july8_bold-webfont.eot');
		src: url('css/print_report/th_k2d_july8_bold-webfont.eot?#iefix') format('embedded-opentype'),
			 url('css/print_report/th_k2d_july8_bold-webfont.woff') format('woff'),
			 url('css/print_report/th_k2d_july8_bold-webfont.ttf') format('truetype');
		font-weight: bold;
		font-style: normal;

	}

	@font-face {
		font-family: 'THK2DJuly8';
		src: url('css/print_report/th_k2d_july8_italic-webfont.eot');
		src: url('css/print_report/th_k2d_july8_italic-webfont.eot?#iefix') format('embedded-opentype'),
			 url('css/print_report/th_k2d_july8_italic-webfont.woff') format('woff'),
			 url('css/print_report/th_k2d_july8_italic-webfont.ttf') format('truetype');
		font-weight: normal;
		font-style: italic;

	}

	@font-face {
		font-family: 'THK2DJuly8';
		src: url('css/print_report/th_k2d_july8-webfont.eot');
		src: url('css/print_report/th_k2d_july8-webfont.eot?#iefix') format('embedded-opentype'),
			 url('css/print_report/th_k2d_july8-webfont.woff') format('woff'),
			 url('css/print_report/th_k2d_july8-webfont.ttf') format('truetype');
		font-weight: normal;
		font-style: normal;

	}


	* {
		font-family: 'THK2DJuly8';
	}

	u {
	  position: relative;
	  text-decoration: none;
	}

	u::after {
	  border-bottom: 1px dotted;
	  bottom: 4px;
	  content: '';
	  height: 0;
	  left: 0;
	  position: absolute;
	  right: 0;
	}
}

</style>
</head>

<body>
<?php
    $month = _thai_month((date("n")));
	$year = _thai_digit((date("Y") + 543));
?>

<h1 class="h2">บัญชีรายนามพระภิกษุสามเณร ผู้จำพรรษาในวัด <u>นาป่าพง</u>   <br />
มีพัทธสีมา มหานิกาย ตำบล    <u>บึงทองหลาง</u>     อำเภอ     <u>ลำลูกกา</u>   จังหวัด   <u>ปทุมธานี</u>    <br />
พระ   <u>อธิการคึกฤทธิ์  โสตฺถิผโล</u>   เจ้าอาวาส นำส่งวันที่    <u>&nbsp;&nbsp;&nbsp;</u>           เดือน  <u><?php echo $month ?></u>   พ.ศ. <u><?php echo $year ?></u> </h1>

<?php
$header_html_table = <<< heredoc
<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<td rowspan="2" class="width-5 center">เลขที่</td>
			<td rowspan="2" class="width-15 bgline">ชื่อ</td>
			<td rowspan="2" class="width-15 bgline">ฉายา</td>
			<td rowspan="2" class="width-12 bgline center">อายุ</td>
			<td rowspan="2" class="width-12 bgline center">พรรษา</td>
			<td colspan="2" class="width-12 bgline center">การศึกษา</td>
			<td rowspan="2" class="width-12 bgline center">แม่ชี</td>
			<td rowspan="2" class="width-12 bgline center">ศิษย์วัด</td>
			<td rowspan="2" class="width-12 bgline center">หมายเหตุ</td>
		</tr>
		<tr>
			<td class="width-12 bgline center">ธรรม</td>
			<td class="width-12 bgline center">บาลี</td>
		</tr>
heredoc;
?><!--สำหรับ heredoc จะมีกฎอยู่นิดหน่อยคือ บรรทัดล่างสุด(บรรทัดปิด)จะต้องไม่มีช่องว่างอยู่ด้านหน้าและด้านหลัง ส่วนตัวแปรสามารถแทรกไปได้เลย-->

<?php
	$i = 0;
	$j = 0;
	$page1 = false;

	// ย้ายไปอยู่ที่อื่น ลาสิกขา มรณภาพ ไม่เอามาแสดง
	$sqlCommand = "SELECT * FROM tbl_bhikkhu WHERE status_id NOT IN (2, 3, 4) ORDER BY phansa_year DESC, position_id, ordinate, ordinate_second";
	$recordset = $objConn->Execute($sqlCommand);
	$recordCount = $recordset->RecordCount();

	while (!$recordset->EOF) :

		if ($i==0) { // แถวแรกสุดของหน้า 1 (ให้หน้าละ 20 แถว)
			echo $header_html_table;
		} elseif ($i%20 == 0 && $page1 == false) { // หน้าแรกและเป็นแถวสุดท้ายก่อนขึ้นหน้าใหม่ (ให้หน้าละ 20 แถว)
			echo '</tbody></table>';
			echo '<div class="page-break"></div>';
			echo $header_html_table;
			$page1 = true;
		} elseif ($page1 == true) { // หน้าอื่นที่ไม่ใช่หน้า 1
			$j = $j + 1;
			if ($j == 23) { // แถวสุดท้ายของหน้าอื่นที่ไม่ใช่หน้า 1 (ให้ปน้าอื่นที่ไม่ใช่หน้า 1 แสดงได้ 23 แถวภายใน 1 หน้า)
				echo '</tbody></table>';
				echo '<div class="page-break"></div>';
				echo $header_html_table;
				$j = 0;
			} else {

			}
		} else {

		}
?>

 <tr>
   <td class="width-5 center"><?php echo ($i+1) . '.'; ?></td>
   <td class="width-15 bgline">
	   <?php
			if ($recordset->fields["position_id"] == 1) {
				echo 'พระอธิการ' . $recordset->fields["name"];
			} elseif ($recordset->fields["position_id"] == 2) {
				echo 'พระ' . $recordset->fields["name"];
			} elseif ($recordset->fields["position_id"] == 3) {
				echo 'สามเณร' . $recordset->fields["name"];
			}
	   ?>
   </td>
   <td class="width-15 bgline">
	   <?php
			if ($recordset->fields["position_id"] == 3) { // สามเณรให้ใช้นามสกุล เพราะไม่มีฉายา
				echo $recordset->fields["surname"];
			} else {
				echo $recordset->fields["alias"];
			}
	   ?>
   </td>
   <td class="width-12 bgline center">
	   <?php
			if ($recordset->fields["age_year"] == 0) {
				echo '-';
			} else {
				echo $recordset->fields["age_year"];
			}
	   ?>
   </td>
   <td class="width-12 bgline center">
	   <?php
			if ($recordset->fields["phansa_year"] == 0) {
				echo '-';
			} else {
				echo $recordset->fields["phansa_year"];
			}
	   ?>
   </td>
   <td class="width-12 bgline center">
	   <?php
			if ($recordset->fields["fair"] != '') {
				echo 'ธรรม';
			} else {
				echo '-';
			}
	   ?>
   </td>
   <td class="width-12 bgline center">
	   <?php
			if ($recordset->fields["graduate"] != '') {
				echo 'บาลี';
			} else {
				echo '-';
			}
	   ?>
   </td>
   <td class="width-12 bgline center">-</td>
   <td class="width-12 bgline center">-</td>
   <td class="width-12 bgline center">
	   <?php
			if ( ($recordset->fields["fair"] != '') && ($recordset->fields["graduate"] != '') ) {
				echo $recordset->fields["fair"] . ', ' . $recordset->fields["graduate"];
			} else {
				if ($recordset->fields["fair"] != '') {
					echo $recordset->fields["fair"];
				}
				if ($recordset->fields["graduate"] != '') {
					echo $recordset->fields["graduate"];
				}
			}
	   ?>
   </td>
 </tr>

<?php
		$i = $i + 1;
		$recordset->MoveNext();

	endwhile;
?>

	</tbody>
</table>


<p class="center">สำรวจเมื่อวันที่ <u>&nbsp;&nbsp;&nbsp;</u> เดือน <u><?php echo $month ?></u> พ.ศ. <u><?php echo $year ?></u></p>
<p class="center margintop">(ลงชื่อ) ................................................... เจ้าอาวาส วัด <u>นาป่าพง</u></p>
</body>
</html>
