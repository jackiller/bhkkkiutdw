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
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>รายนามภิกขุและสามเณร วัดนาป่าพง</title>
<link href="css/print_report/print.css" rel="stylesheet" type="text/css"  media="all"/>
</head>

<body>
<?php
	$bhikkhu_sort = array();

//--------------------------------------> ดึงข้อมูลพระทั้งหมดที่มีอายุพรรษา >= 5 ปี (รวมทั้งอาคันตุกะด้วย) ขึ้นไปมาแสดงก่อน เรียงลำดับตามพรรษาจากมากไปน้อย
	$sqlCommand = "SELECT * FROM tbl_bhikkhu WHERE phansa_year >= 5 ORDER BY phansa_year DESC, ordinate";
	$recordset = $objConn->Execute($sqlCommand);
	$recordCount = $recordset->RecordCount();

	while (!$recordset->EOF) :
		$bhikkhu_sort[] = $recordset->fields["bhikkhu_id"];
		$recordset->MoveNext();
	endwhile;

//--------------------------------------> ดึงข้อมูลพระทั้งหมดที่มีอายุพรรษา < 5 ปี และไม่เป็นอาคันตุกะ มาแสดงเป็นลำดับต่อไป
	$sqlCommand = "SELECT * FROM tbl_bhikkhu WHERE phansa_year < 5 AND position_extra_id <> 4 ORDER BY phansa_year DESC, ordinate";
	$recordset = $objConn->Execute($sqlCommand);
	$recordCount = $recordset->RecordCount();

	while (!$recordset->EOF) :
		$bhikkhu_sort[] = $recordset->fields["bhikkhu_id"];
		$recordset->MoveNext();
	endwhile;

//--------------------------------------> ดึงข้อมูลพระทั้งหมดที่มีอายุพรรษา < 5 ปี และเป็นอาคันตุกะ มาแสดงเป็นลำดับต่อไป
	$sqlCommand = "SELECT * FROM tbl_bhikkhu WHERE phansa_year < 5 AND position_extra_id = 4 ORDER BY phansa_year DESC, ordinate";
	$recordset = $objConn->Execute($sqlCommand);
	$recordCount = $recordset->RecordCount();

	while (!$recordset->EOF) :
		$bhikkhu_sort[] = $recordset->fields["bhikkhu_id"];
		$recordset->MoveNext();
	endwhile;


//------------------------------------> loop แสดงข้อมูล
	$i = 0;

	foreach ($bhikkhu_sort as $key_id) :

		$sqlCommand = "SELECT * FROM tbl_bhikkhu WHERE bhikkhu_id =" . $key_id . " LIMIT 1";
		$recordset = $objConn->Execute($sqlCommand);

		$sqlCommand = "SELECT * FROM tbl_position WHERE position_id=" . $recordset->fields["position_id"] . " LIMIT 1";
		$recordsetPosition = $objConn->Execute($sqlCommand);

		$sqlCommand = "SELECT * FROM tbl_position WHERE position_id=" . $recordset->fields["position_extra_id"] . " LIMIT 1";
		$recordsetPositionExtra = $objConn->Execute($sqlCommand);

		if ($i==0 || $i%11 == 0) { // ให้หน้าละ 11 แถว
			echo '<h1 class="h1">รายนามภิกขุและสามเณร</h1>';
			echo '<h2 class="h2">วัดนาป่าพง</h2>';
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="td-border-top td-border-foot">';
		} else {
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="td-border-foot">';
		}
?>

		<tr>
		  <td class="td-width-10"><?php echo _thai_digit($i+1); ?></td>
		  <?php
			if ($recordsetPositionExtra->fields["position_desc"] != '') :
				$recordsetPositionExtra = '(' . $recordsetPositionExtra->fields["position_desc"] . ')';
			else:
				$recordsetPositionExtra = '';
			endif;
		  ?>
		  <td class="td-width-80">
				<span class="block name"><?php echo $recordsetPosition->fields["position_desc"]; ?></span>
				<span class="block red nickname"><?php echo $recordsetPositionExtra; ?>&nbsp;</span>
				<span class="block text_white">&nbsp;</span>
		  </td>
		  <td class="td-width-40">
			<span class="block name"><?php echo $recordset->fields["name"]; ?></span>
			<span class="block nickname">(<?php echo $recordset->fields["nickname"]; ?>)</span>
			<span class="block text_white">&nbsp;</span>
		  </td>
		  <td class="td-width-50">
			<span class="block name"><?php echo $recordset->fields["alias"]; ?></span>
			<span class="block nickname"><?php echo $recordset->fields["alias_meaning"]; ?></span>
			<span class="block text_white">&nbsp;</span>
		  </td>

		  <td class="td-width-80">
			<span class="block name">พรรษา</span>
			<span class="block nickname">อุปสมบท</span>
			<span class="block meaning">กุฏิ</span>
		  </td>

		  <td class="td-width-80">
			<!--<span class="block"><?php //echo _calculate_phansa($recordset->fields["ordinate"]); ?></span>-->
			<span class="block bolder name"><?php echo _thai_digit($recordset->fields["phansa_year"]); ?></span>
			<span class="block nickname"><?php echo _thai_digit(format_date_thai($recordset->fields["ordinate"])); ?></span>
			<span class="block meaning"><?php echo _thai_digit($recordset->fields["kuti"]); ?></span>
		  </td>

		  <td class="tdimg"><img src="uploads/<?php echo $recordset->fields["face_image"]; ?>" /></td>

		</tr>
	  </table>

<?php
		$i = $i + 1;

	endforeach;
?>

</body>

</html>
