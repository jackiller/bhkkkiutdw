<?php include("report-bhikkhu-ubosot.php"); ?>
<?php
	require("../../xcrud/functions.php");

	$xcrud = Xcrud::get_instance();
	$db = Xcrud_db::get_instance(); // ถ้ามี operation ที่ต้องทำกับ db ให้สร้าง instance db ขึ้นมาก่อน

	if (isset($_POST["BTTclear"])) {
		$query = "TRUNCATE TABLE tbl_temp_ubosot";
		$db->query($query);
		$xcrud->set_exception('', 'ล้างรายการ เรียบร้อยแล้ว', 'success');
	}

//------------------------------------------->
	$xcrud->language('th');
	$xcrud->table('tbl_bhikkhu');
	$xcrud->join('bhikkhu_id','tbl_temp_ubosot','bhikkhu_id', 'b');
	$xcrud->table_name('รายชื่อลงอุโบสถ');


	// ต้องการให้ field search ตอน inner join เป็น drop down ให้เลือกได้ให้ใช้คำสั่งด้านล่างนี้ ( !!! ปิดคำสั่ง $xcrud->relation ออกด้วย !!!)
	//---> ต้องการทำให้ช่อง search เป็น dropdown และมี relation กันด้วย
	unset($arr_data);
	$arr_data = render_array_dropdown('tbl_position', 'position_id', 'position_desc'); // ตาราง master
	$xcrud->change_type('position_id','select', '', array('values'=>$arr_data)); // ทำให้ช่อง search เป็น dropdown

	unset($arr_data);
	$arr_data = render_array_dropdown('tbl_status', 'status_id', 'status_desc'); // ตาราง master
	$xcrud->change_type('status_id','select', true, array('values'=>$arr_data)); // ทำให้ช่อง search เป็น dropdown

	unset($arr_data);
	$arr_data = render_array_dropdown('tbl_position', 'position_id', 'position_desc'); // ตาราง master
	$xcrud->change_type('position_extra_id','select', '', array('values'=>$arr_data)); // ทำให้ช่อง search เป็น dropdown
	//<---

	$xcrud->subselect('age',1); // เพิ่มคอลัมน์ใหม่เข้ามา ซึ่งเป็นคอลัมน์ที่ไม่มีใน db table (ต้องวางคำสั่งนี้ไว้ก่อนจะใช้ $xcrud->label)
	$xcrud->column_callback('age','calculate_age'); // คอลัมน์ใหม่ที่เพิ่มเข้ามา call function calculate_age ในไฟล์ /xcrud/functions.php

	$xcrud->subselect('phansa',1); // เพิ่มคอลัมน์ใหม่เข้ามา ซึ่งเป็นคอลัมน์ที่ไม่มีใน db table (ต้องวางคำสั่งนี้ไว้ก่อนจะใช้ $xcrud->label)
	$xcrud->column_callback('phansa','calculate_phansa'); // คอลัมน์ใหม่ที่เพิ่มเข้ามา call function calculate_phansa ในไฟล์ /xcrud/functions.php

	// กำหนดชื่อคอลัมน์
	$xcrud->label(array(
		'bhikkhu_id' => 'รหัส',
		'face_image' => 'รูปภาพ',
		'position_id' => 'ตำแหน่ง',
		'name' => 'ชื่อ',
		'surname' => 'นามสกุล',
		'nickname' => 'ชื่อเล่น',
		'alias' => 'ฉายา',
		'alias_meaning' => 'ความหมายฉายา',
		'position_extra_id' => 'ตำแหน่งพิเศษ',
		'ordinate' => 'อุปสมบท / บรรพชาสามเณร',
		'phansa_year' => 'พรรษา', // เป็นพรรษาที่บันทึกลงใน db จากการคำนวณ $xcrud->column_callback('phansa','calculate_phansa') เพื่อใช้เป็นเงื่อนไขในการ sort หน้าแสดงรายงานภิกขุก่อนพิมพ์
		'kuti' => 'กุฎิ',
		'birthday' => 'วันเกิด',
		'address' => 'ที่อยู่',
		'status_id' => 'สถานะ',
		'bhikkhu_opinion' => 'ความเห็นหมู่คณะต่อพฤติกรรม',
		'age' => 'อายุ', // column นี้ไม่มีใน db ใช้คำสั่ง subselect เพิ่มเข้ามาเอง
		'phansa' => 'พรรษา', // column นี้ไม่มีใน db ใช้คำสั่ง subselect เพิ่มเข้ามาเอง
		'offence' => 'อาบัติหนัก'
	));

	// ระบุคอลัมน์ที่ต้องการให้แสดงในหน้า list view
	$xcrud->columns('face_image, position_id, name, surname, nickname, age, alias, position_extra_id, ordinate, phansa, kuti, status_id');
	$xcrud->column_width('ordinate', '180px');
	$xcrud->column_width('offence', '80px');

	// image upload & resize & manual crop
	// ถ้าต้องการตัดรูปขนาด 900x200 ให้เอา 900/200 จะได้ ratio = 4.5
	// ในตัวอย่างนี้ต้องการ crop รูปขนาด 100x100 ให้เอา 100/100 ได้ ratio = 1 จากนั้นก็ให้ resize รูปลงเหลือขนาด 100x100 (width x height)
	$xcrud->change_type('face_image', 'image', '', array('ratio' => 1, 'width' => 100, 'height' => 100));

	if (in_array(3, $_SESSION['jigowatt']['user_level'])) { // 3 = only add role
		$xcrud->unset_remove();
	}

	if (in_array(4, $_SESSION['jigowatt']['user_level'])) { // 4 = only view role
		$xcrud->unset_remove();
	}

    $xcrud->unset_add();
    $xcrud->unset_edit();
    $xcrud->unset_view();
    $xcrud->unset_csv();
    //$xcrud->unset_limitlist();
    //$xcrud->unset_numbers();
    //$xcrud->unset_pagination();
    $xcrud->unset_print();
    $xcrud->unset_search();
    //$xcrud->unset_title();
    $xcrud->unset_sortable();

	$xcrud->replace_remove('delete_data_tbl_temp_ubosot'); // replaces standard xcrud actions (remove) by custom function ใช้กรณี เอาตาราง a join ตาราง b แต่ตอนกดปุ่มลบถ้าเป็น standard มันจะลบให้ 2 ตารางเลย แต่เราต้องการลบแค่ตาราง a ตารางเดียว เลยต้องใข้ replace_remove
	echo $xcrud->render();

?>

<?php if (in_array(2, $_SESSION['jigowatt']['user_level'])) : // 2 = special ?>
<form action="" method="POST">
	<input type="submit" name="BTTclear" id="BTTclear" value="ล้างรายการลงอุโบสถ" class="btn btn-danger xcrud-action" />
</form>
<?php endif; ?>

<script>
	$( document ).ready(function() {
		$("#BTTclear").click(function() {
			if (!confirm("ต้องการล้างรายการ ใช่หรือไม่ ?")) {
				return false;
			}
		});
	});
</script>
