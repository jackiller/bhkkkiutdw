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
<title>รายนามภิกขุ-สามเณรลงอุโบสถ วัดนาป่าพง</title>
<link href="css/print_report/print.css" rel="stylesheet" type="text/css"  media="all"/>
</head>

<body>
<h1 class="h1">รายนามภิกขุ-สามเณรลงอุโบสถ</h1>
<h2 class="h2">วัดนาป่าพง</h2>
<?php
	$bhikkhu_sort = array();
	$arr_ubosot = array();

//--------------------------------------> ดึง id ภิกขุที่จะลงอุโบสถออกมาทั้งหมด
	$sqlCommand = "SELECT * FROM tbl_temp_ubosot";
	$recordset = $objConn->Execute($sqlCommand);
	$recordCount = $recordset->RecordCount();

	while (!$recordset->EOF) :
		$arr_ubosot[] = $recordset->fields["bhikkhu_id"];
		$recordset->MoveNext();
	endwhile;

//--------------------------------------> ดึงข้อมูลพระ 2 รูปที่มีอายุพรรษา >= 5 ปี (ไม่รวมทั้งอาคันตุกะ) ขึ้นไปมาแสดงก่อน เรียงลำดับตามพรรษาจากมากไปน้อย (ไม่เอาสามเณร, ไม่เอาพระที่อาบัติหนัก, ไม่เอาไปอยู่ที่อื่น, ไม่เอาลาสิกขา, ไม่เอามรณภาพ)
    // ตอนลงอุโบสถกฎคือ ต้องมีพระวัดนานำมาก่อน 2 รูป (ไม่เอาพระที่เป็นอาคันตุกะ ถึงจะมีพรรษามากกว่าพระวัดนาก็ตาม)
	// position_id = 3 คือ สามเณร
	// position_extra_id = 1 คือ อาคันตุกะ
	$sqlCommand = "SELECT * FROM tbl_bhikkhu WHERE bhikkhu_id IN (" . implode(',', $arr_ubosot) . ")" . " AND phansa_year >= 5 AND position_extra_id <> 1 AND position_id <> 3 AND offence = 'ไม่มี' ORDER BY phansa_year DESC, ordinate LIMIT 2";
	$recordset = $objConn->Execute($sqlCommand);
	$recordCount = $recordset->RecordCount();

	while (!$recordset->EOF) :
		$bhikkhu_sort[] = $recordset->fields["bhikkhu_id"];
		$recordset->MoveNext();
	endwhile;

	unset($arr_ubosot); // clear array

	// ดึง id ภิกขุที่จะลงอุโบสถออกมาทั้งหมด โดยไม่เอา 2 รูปแรกที่ได้จาก logic ข้างต้น
	$sqlCommand = "SELECT * FROM tbl_temp_ubosot WHERE bhikkhu_id NOT IN (" . implode(',', $bhikkhu_sort) . ")";
	$recordset = $objConn->Execute($sqlCommand);
	$recordCount = $recordset->RecordCount();

	while (!$recordset->EOF) :
		$arr_ubosot[] = $recordset->fields["bhikkhu_id"];
		$recordset->MoveNext();
	endwhile;

//--------------------------------------> ดึงข้อมูลพระทั้งหมดที่มีอายุพรรษา >= 5 ปี (รวมทั้งอาคันตุกะด้วย) ขึ้นไปมาแสดงก่อน เรียงลำดับตามพรรษาจากมากไปน้อย (ไม่เอาสามเณร, ไม่เอาพระที่อาบัติหนัก)
	// position_id = 3 คือ สามเณร
	$sqlCommand = "SELECT * FROM tbl_bhikkhu WHERE bhikkhu_id IN (" . implode(',', $arr_ubosot) . ")" . " AND phansa_year >= 5 AND position_id <> 3 AND offence = 'ไม่มี' ORDER BY phansa_year DESC, ordinate";
	$recordset = $objConn->Execute($sqlCommand);
	$recordCount = $recordset->RecordCount();

	while (!$recordset->EOF) :
		$bhikkhu_sort[] = $recordset->fields["bhikkhu_id"];
		$recordset->MoveNext();
	endwhile;

//--------------------------------------> ดึงข้อมูลพระทั้งหมดที่มีอายุพรรษา < 5 ปี และไม่เป็นอาคันตุกะ มาแสดงเป็นลำดับต่อไป (ไม่เอาสามเณร, ไม่เอาพระที่อาบัติหนัก)
	// position_id = 3 คือ สามเณร
	// position_extra_id = 1 คือ อาคันตุกะ
	$sqlCommand = "SELECT * FROM tbl_bhikkhu WHERE bhikkhu_id IN (" . implode(',', $arr_ubosot) . ")" . " AND phansa_year < 5 AND position_extra_id <> 1 AND position_id <> 3 AND offence = 'ไม่มี' ORDER BY phansa_year DESC, ordinate";
	$recordset = $objConn->Execute($sqlCommand);
	$recordCount = $recordset->RecordCount();

	while (!$recordset->EOF) :
		$bhikkhu_sort[] = $recordset->fields["bhikkhu_id"];
		$recordset->MoveNext();
	endwhile;

//--------------------------------------> ดึงข้อมูลพระทั้งหมดที่มีอายุพรรษา < 5 ปี และเป็นอาคันตุกะ มาแสดงเป็นลำดับต่อไป (ไม่เอาสามเณร, ไม่เอาพระที่อาบัติหนัก)
	// position_id = 3 คือ สามเณร
	// position_extra_id = 1 คือ อาคันตุกะ
	$sqlCommand = "SELECT * FROM tbl_bhikkhu WHERE bhikkhu_id IN (" . implode(',', $arr_ubosot) . ")" . " AND phansa_year < 5 AND position_extra_id = 1 AND position_id <> 3 AND offence = 'ไม่มี' ORDER BY phansa_year DESC, ordinate";
	$recordset = $objConn->Execute($sqlCommand);
	$recordCount = $recordset->RecordCount();

	while (!$recordset->EOF) :
		$bhikkhu_sort[] = $recordset->fields["bhikkhu_id"];
		$recordset->MoveNext();
	endwhile;

//--------------------------------------> ดึงข้อมูลสามเณรทั้งหมด มาแสดงเป็นลำดับต่อไป เรียงตามวันที่อุปสมบท (สามเณรไม่มีพรรษา)
	// position_id = 3 คือ สามเณร
	$sqlCommand = "SELECT * FROM tbl_bhikkhu WHERE bhikkhu_id IN (" . implode(',', $arr_ubosot) . ")" . " AND offence = 'ไม่มี' AND position_id = 3 ORDER BY ordinate, bhikkhu_id";
	$recordset = $objConn->Execute($sqlCommand);
	$recordCount = $recordset->RecordCount();

	while (!$recordset->EOF) :
		$bhikkhu_sort[] = $recordset->fields["bhikkhu_id"];
		$recordset->MoveNext();
	endwhile;

//--------------------------------------> ดึงข้อมูลพระที่อาบัติหนักทั้งหมด มาแสดงเป็นลำดับต่อไป เรียงตามพรรษาจากมากไปน้อย
	$sqlCommand = "SELECT * FROM tbl_bhikkhu WHERE bhikkhu_id IN (" . implode(',', $arr_ubosot) . ")" . " AND offence = 'มี' ORDER BY phansa_year DESC, ordinate";
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

		$sqlCommand = "SELECT * FROM tbl_position_extra WHERE position_extra_id=" . $recordset->fields["position_extra_id"] . " LIMIT 1";
		$recordsetPositionExtra = $objConn->Execute($sqlCommand);

		if ($i==0) { // ให้หน้าละ 11 แถว
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="td-border-top td-border-foot">';
		} elseif ($i%11 == 0) {
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="td-border-top td-border-foot endpage margintop">';
		} else {
			echo '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="td-border-foot">';
		}
?>

		<tr>
		  <td class="td-width-10"><?php echo _thai_digit($i+1); ?></td>
		  <?php
			if ($recordsetPositionExtra->fields["position_extra_desc"] != '') :
				$recordsetPositionExtra = '(' . $recordsetPositionExtra->fields["position_extra_desc"] . ')';
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
			<span class="block name">
			<?php
				if ($recordset->fields["position_id"] == 3) { // สามเณรไม่มีฉายา ใช้นามสกุลแทน
					echo $recordset->fields["surname"];
				} else {
					echo $recordset->fields["alias"];
				}
			?>
			</span>
			<span class="block nickname">
			<?php
				if ($recordset->fields["position_id"] == 3) { // สามเณรไม่มีความหมายฉายา
					echo "&nbsp;";
				} else {
					echo $recordset->fields["alias_meaning"];
				}
			?>
			</span>
			<span class="block text_white">&nbsp;</span>
		  </td>

		  <td class="td-width-80">
			<?php
				if ($recordset->fields["position_id"] == 3) { // สามเณรไม่มีพรรษา
					echo "&nbsp;";
				} else {
					echo "<span class='block name'>พรรษา</span>";
				}
			?>
			<?php
				if ($recordset->fields["position_id"] == 3) { // สามเณรใช้ บรรพชา
					echo "<span class='block nickname'>บรรพชา</span>";
				} else {
					echo "<span class='block nickname'>อุปสมบท</span>";
				}
			?>
			<span class="block meaning">กุฏิ</span>
		  </td>

		  <td class="td-width-80">
			<span class="block bolder name">
			<?php
				if ($recordset->fields["position_id"] == 3) { // สามเณรไม่มีพรรษา
					echo "&nbsp;";
				} else {
					echo _thai_digit($recordset->fields["phansa_year"]);
				}
			?>
			</span>
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
