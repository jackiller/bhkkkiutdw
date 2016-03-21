<?php

	$xcrud = Xcrud::get_instance();
	$xcrud->language('th');
	$xcrud->table('tbl_lent');

	$db = Xcrud_db::get_instance(); // ถ้ามี operation ที่ต้องทำกับ db ให้สร้าง instance db ขึ้นมาก่อน

	// กำหนดชื่อตาราง
	$xcrud->table_name('ข้อมูลเข้าพรรษา-ออกพรรษา');

	// กำหนดชื่อคอลัมน์
	$xcrud->label(array(
		'lent_year' => 'ปี (ค.ศ.)',
		'lent_in_first' => 'วันเข้าพรรษา (แรก)',
		'lent_out_first' => 'วันออกพรรษา (แรก)',
		'lent_in_last' => 'วันเข้าพรรษา (หลัง)',
		'lent_out_last' => 'วันออกพรรษา (หลัง)'
	));

	// ซ่อนปุ่ม standard view
	$xcrud->unset_view();

	// ซ่อนปุ่ม standard (หน้าเพิ่ม, หน้าแก้ไข)
	$xcrud->hide_button('save_new, save_edit');

	// validate required field
	$xcrud->validation_required('lent_year, lent_in_first, lent_out_first, lent_in_last, lent_out_last');

	if (in_array(3, $_SESSION['jigowatt']['user_level'])) { // 3 = only add role
		$xcrud->unset_edit();
		$xcrud->unset_remove();
	}

	if (in_array(4, $_SESSION['jigowatt']['user_level'])) { // 4 = only view role
		$xcrud->unset_add();
		$xcrud->unset_edit();
		$xcrud->unset_remove();
	}

//	$xcrud->benchmark(true); // วัดประสิทธิภาพความเร็วในการรันหน้านี้

	echo $xcrud->render();

?>

<script>
//	// ให้แสดง message type success ถ้า save ข้อมูลแล้วไม่มี error
//	jQuery(document).on("xcrudafterrequest", function(event, container) {
//		if (Xcrud.current_task === 'save') { // กดปุ่ม save
//			if(!jQuery(".xcrud-message").size()) { // ไม่เจอ class .xcrud-message (message class ที่มาจาก set_exception, validation_required, validation_pattern)
//				Xcrud.show_message(container, 'บันทึกข้อมูลเข้าระบบสำเร็จ', 'success'); // ให้แสดง message success
//			}
//		}
//	});
//
//	// double click ที่แถวแล้วโดดไปที่หน้าแก้ไขข้อมูล
//	$(".xcrud").on("dblclick","tr.xcrud-row",function() {
//		var primary = $(this).find(".xcrud-actions a.xcrud-action:first").data("primary");
//		var container = $(this).closest(".xcrud-ajax");
//		Xcrud.request(container,Xcrud.list_data(container,{task:'edit',primary:primary}));
//	});
</script>