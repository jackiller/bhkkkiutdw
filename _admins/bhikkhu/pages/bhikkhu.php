<?php
	if (@$_GET['mode'] == "print") : //พิมพ์รายงาน (ติดบอร์ด)
		include("report-bhikkhu.php");
	endif;
?>

<?php

	require("../../xcrud/functions.php");

	$xcrud = Xcrud::get_instance();
	$xcrud->language('th');
	$xcrud->table('tbl_bhikkhu');
	$xcrud->default_tab('ข้อมูลทั่วไป'); // ทำ nested table ให้เป็น tab

	$db = Xcrud_db::get_instance(); // ถ้ามี operation ที่ต้องทำกับ db ให้สร้าง instance db ขึ้นมาก่อน

	// กำหนดชื่อตาราง
	if (@$_GET['mode'] == "print") : //พิมพ์รายงาน (ติดบอร์ด)
		$xcrud->table_name('พิมพ์รายนามภิกขุ-สามเณร (ติดบอร์ด)');
	else:
		$xcrud->table_name('รายนามภิกขุ-สามเณร');
	endif;

	// inner join table
	// $xcrud->relation(main_table_relation_field, target_table, row_id_from_target_table, target_field_name)
	// ใช้คำสั่งนี้จะได้ field (หน้าเพิ่ม, หน้าแก้ไข) เป็น drop down ให้
	// field ตอนเลือก search ถ้าใช้คำสั่งนี้ตอน search เราต้องพิมพ์เอง ไม่ได้เป็น dropdown ให้เราเลือก
	//
	//$xcrud->relation('position_id','tbl_position','position_id','position_desc');
	//$xcrud->relation('status_id','tbl_status','status_id','status_desc');
	//$xcrud->relation('position_extra_id','tbl_position','position_id','position_desc');

	// ต้องการให้ field search ตอน inner join เป็น drop down ให้เลือกได้ให้ใช้คำสั่งด้านล่างนี้ ( !!! ปิดคำสั่ง $xcrud->relation ออกด้วย !!!)
	//---> ต้องการทำให้ช่อง search เป็น dropdown และมี relation กันด้วย
	unset($arr_data);
	$arr_data = render_array_dropdown('tbl_position', 'position_id', 'position_desc'); // ตาราง master
	$xcrud->change_type('position_id','select', '', array('values'=>$arr_data)); // ทำให้ช่อง search เป็น dropdown

	unset($arr_data);
	$arr_data = render_array_dropdown('tbl_status', 'status_id', 'status_desc'); // ตาราง master
	$xcrud->change_type('status_id','select', true, array('values'=>$arr_data)); // ทำให้ช่อง search เป็น dropdown
	$xcrud->pass_default('status_id', 1); // default value dropdown

	unset($arr_data);
	$arr_data = render_array_dropdown('tbl_position_extra', 'position_extra_id', 'position_extra_desc'); // ตาราง master
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
	$xcrud->columns('face_image, position_id, name, surname, nickname, age, alias, position_extra_id, ordinate, phansa, kuti, status_id, offence');
	$xcrud->column_width('ordinate', '180px');
	$xcrud->column_width('offence', '80px');

	// ซ่อน field (หน้าเพิ่ม, หน้าแก้ไข)
	$xcrud->fields("age, phansa, phansa_year", true);

	// disable field ไม่ให้แก้ไขได้ในหน้า edit และหน้า add
	//$xcrud->disabled_on_edit('phansa_year');
	//$xcrud->readonly_on_create('phansa_year');

	// เปลี่ยนคอลัมน์เป็น textarea (หน้าเพิ่ม, หน้าแก้ไข)
	$xcrud->change_type('address, bhikkhu_opinion','textarea');

	// สำหรับแปลงปี คศ เป็นปี พศ ในหน้า list
	// แปลงตัวแปร type date ปกติ xcrud จะทำเป็นปฎิทิน แต่เราจะให้เป็น textbox
//	$xcrud->change_type('ordinate', 'text', '', array('id'=>'TXTordinate')); // ใส่ attrubute id เข้าไปใน control
//	$xcrud->change_type('birthday', 'text', '', array('id'=>'TXTbirthday')); // ใส่ attrubute id เข้าไปใน control

	// image upload & resize & manual crop
	// ถ้าต้องการตัดรูปขนาด 900x200 ให้เอา 900/200 จะได้ ratio = 4.5
	// ในตัวอย่างนี้ต้องการ crop รูปขนาด 100x100 ให้เอา 100/100 ได้ ratio = 1 จากนั้นก็ให้ resize รูปลงเหลือขนาด 100x100 (width x height)
	$xcrud->change_type('face_image', 'image', '', array('ratio' => 1, 'width' => 100, 'height' => 100, 'manual_crop' => true));

	// ภิกษุอาบัติหนักให้แสดงแถวเป็นสีแดง ต้องมี field ในคำสั่ง $xcrud->columns();
	$xcrud->highlight_row('offence', '=', 'มี', '#FFE6E6');

	// ซ่อนปุ่ม standard (หน้าเพิ่ม, หน้าแก้ไข)
	$xcrud->hide_button('save_new, save_edit');

	// validate required field
	// ถ้าใช้ validation_required, validation_pattern จะไม่สามารถ custom message error ตามที่เราต้องการได้
	// และไม่ว่าจะ error อะไรก็จะมี message error แค่ message เดียวเท่านั้นเอง (message ระบบดูได้ที่ xcrud/languages/th.ini ค่าที่อยู่ในตัวแปร validation_error)
	// ที่ใช้ตัวนี้เพราะระบบมีการ upload รูปภาพแบบ crop ได้ เวลาที่ user เลือกรูป crop แล้วเกิดใส่ข้อมูลใน field อื่น ๆ ไม่ครบหรือไม่ได้ใส่ พอกด submit รูปภาพที่ crop ไว้จะไม่หายไป (เพราะมัน check ที่ client side)
	$xcrud->validation_required('position_id, name, surname, nickname, ordinate, birthday, address, status_id');

	// แต่ถ้าต้องการ custom message error เอง ให้เราใช้ callback function เช่น $xcrud->before_insert()
	// ซึ่งตัว callback จะมีข้อเสียคือเมื่อระบบมีการ upload รูปภาพแบบ crop ได้ เวลาที่ user เลือกรูป crop แล้วเกิดใส่ข้อมูลใน field อื่น ๆ ไม่ครบหรือไม่ได้ใส่ พอกด submit รูปภาพที่ crop ไว้จะหายไป (เพราะมัน check ที่ server side จะมีการ refresh page เป็นผลให้ภาพหายไป)
	// ซึ่งตรงนี้ยังหาวิธีแก้ไขไม่ได้ เลยจำเป็นต้องใช้ validation_required, validation_pattern แทนไปก่อน

	// เพิ่มปุ่ม action เพิ่มรายชื่อพระที่จะลงอุโบสถ
	if (in_array(2, $_SESSION['jigowatt']['user_level']) || in_array(3, $_SESSION['jigowatt']['user_level'])) { // 2 = special, 3 = only add role

		if (@$_GET['mode'] != "print") {
			$xcrud->create_action('add_to_ubosot', 'ubosot_action'); // action callback, function ubosot_action() in functions.php
			$xcrud->button('#', 'เพิ่มรายชื่อลงอุโบสถ', 'glyphicon glyphicon-plus-sign', 'xcrud-action btn-success',
				array(  // set action vars to the button
					'data-task' => 'action',
					'data-action' => 'add_to_ubosot',
					'data-primary' => '{bhikkhu_id}')
			);
		}
	}

	if (in_array(3, $_SESSION['jigowatt']['user_level'])) { // 3 = only add role
		$xcrud->unset_remove();
	}

	if (in_array(4, $_SESSION['jigowatt']['user_level'])) { // 4 = only view role
		$xcrud->unset_add();
		$xcrud->unset_edit();
		$xcrud->unset_remove();
	}

	if (@$_GET['mode'] == "print") { //พิมพ์รายงาน (ติดบอร์ด)
		$xcrud->unset_add();
		$xcrud->unset_edit();
		$xcrud->unset_remove();
		$xcrud->unset_view();
	}

//	$xcrud->benchmark(true); // วัดประสิทธิภาพความเร็วในการรันหน้านี้


//***************************************************************************************************
//*                                         NESTED GRID (document data)
//***************************************************************************************************

	$xcrud_document = $xcrud->nested_table('ข้อมูลเอกสาร','bhikkhu_id','tbl_document','bhikkhu_id'); // nested table
	$xcrud_document->language('th');
	$xcrud_document->default_tab('ข้อมูลเอกสาร'); // ทำ nested table ให้เป็น tab

    $xcrud_document->table_name('ข้อมูลเอกสาร');

	// กำหนดชื่อคอลัมน์
	$xcrud_document->label(array(
		'document_name' => 'รูปภาพเอกสาร'
	));

	// ระบุคอลัมน์ที่ต้องการให้แสดงในหน้า list view
	$xcrud_document->columns('document_name');

	// ซ่อนคอลัมน์ (หน้าเพิ่ม, หน้าแก้ไข)
	$xcrud_document->fields("bhikkhu_id", true);

	// image upload
	$xcrud_document->change_type('document_name', 'image', ''); // ไม่มีกำหนด parameter อะไร นั่นคือเอารูปขนาดเท่าของจริง upload ไปเลย

	// ซ่อนปุ่ม standard
	$xcrud_document->unset_search();
	$xcrud_document->unset_limitlist();
	$xcrud_document->unset_pagination();
	$xcrud_document->unset_csv();
	$xcrud_document->unset_print();

	// ซ่อนปุ่ม standard (หน้าเพิ่ม, หน้าแก้ไข)
	$xcrud_document->hide_button('save_new, save_edit');

	if (in_array(3, $_SESSION['jigowatt']['user_level'])) { // 3 = only add role
		$xcrud_document->unset_edit();
		$xcrud_document->unset_remove();
	}

	if (in_array(4, $_SESSION['jigowatt']['user_level'])) { // 4 = only view role
		$xcrud_document->unset_add();
		$xcrud_document->unset_edit();
		$xcrud_document->unset_remove();
	}

	// override standard edit view
	// กรณีที่ทำ nested table แบบเป็น tab พอกดเข้าไปที่ edit view ของตัว nestd table ต้องการเปลี่ยน wording ของปุ่มใน nested table ให้ต่างจากปุ่มใน parent table
	$xcrud_document->load_view('create','xcrud_nested_detail_view.php'); // file locate in xcrud/themes/your_current_theme/your_file_theme.php
	$xcrud_document->load_view('edit','xcrud_nested_detail_view.php'); // file locate in xcrud/themes/your_current_theme/your_file_theme.php

//	$xcrud_document->benchmark(true); // วัดประสิทธิภาพความเร็วในการรันหน้านี้

//***************************************************************************************************
//*                                         NESTED GRID (history data)
//***************************************************************************************************

	$xcrud_history = $xcrud->nested_table('ข้อมูลสถานะ','bhikkhu_id','tbl_history','bhikkhu_id'); // nested table
	$xcrud_history->language('th');
	$xcrud_history->default_tab('ข้อมูลเอกสาร'); // ทำ nested table ให้เป็น tab

    $xcrud_history->table_name('ข้อมูลสถานะ');

	//---> ต้องการทำให้ช่อง search เป็น dropdown และมี relation กันด้วย
	unset($arr_data);
	$arr_data = render_array_dropdown('tbl_status', 'status_id', 'status_desc'); // ตาราง master
	$xcrud_history->change_type('status_id','select', '', array('values'=>$arr_data)); // ทำให้ช่อง search เป็น dropdown
	//<---

	// กำหนดชื่อคอลัมน์
	$xcrud_history->label(array(
		'status_id' => 'สถานะ',
		'start_date' => 'วันที่เริ่มต้น'
	));

	// ระบุคอลัมน์ที่ต้องการให้แสดงในหน้า list view
	$xcrud_history->columns('status_id, start_date');

	// ซ่อนคอลัมน์ (หน้าเพิ่ม, หน้าแก้ไข)
	$xcrud_history->fields("bhikkhu_id", true);

	// ซ่อนปุ่ม action standard
	$xcrud_history->unset_view();
	$xcrud_history->unset_add();

	// ซ่อนปุ่ม standard
	$xcrud_history->unset_search();
	$xcrud_history->unset_limitlist();
	$xcrud_history->unset_pagination();
	$xcrud_history->unset_csv();
	$xcrud_history->unset_print();

	// ซ่อนปุ่ม standard (หน้าเพิ่ม, หน้าแก้ไข)
	$xcrud_history->hide_button('save_new, save_edit');

	if (in_array(3, $_SESSION['jigowatt']['user_level'])) { // 3 = only add role
		$xcrud_history->unset_edit();
		$xcrud_history->unset_remove();
	}

	if (in_array(4, $_SESSION['jigowatt']['user_level'])) { // 4 = only view role
		$xcrud_history->unset_add();
		$xcrud_history->unset_edit();
		$xcrud_history->unset_remove();
	}

//	$xcrud_history->benchmark(true); // วัดประสิทธิภาพความเร็วในการรันหน้านี้

//***************************************************************************************************
//*                                           RENDER                                                *
//***************************************************************************************************

	// แปลงปี คศ เป็นปี พศ ในหน้า list
//	$xcrud->column_callback('ordinate','callback_format_date_column');
//	$xcrud->column_callback('birthday','callback_format_date_column');
//	$xcrud->before_view('format_date_before_edit');
//	$xcrud->before_edit('format_date_before_edit');
//	$xcrud->before_insert('format_date_value'); // callback function in xcrud/functions.php
//	$xcrud->before_update('format_date_value'); // callback function in xcrud/functions.php

	// ถ้ามี nested table ให้สั่ง callback ที่ object ตัวหลัก จากตัวอย่างนี้ object หลักคือ $xcrud
	// callback event
//	$xcrud->before_insert('validate_bhikku_data'); // callback function in xcrud/functions.php

	$xcrud->after_insert('update_history_data'); // callback function in xcrud/functions.php
	$xcrud->after_update('update_history_data'); // callback function in xcrud/functions.php
	$xcrud->before_remove('check_document_file'); // callback function in xcrud/functions.php
	$xcrud->after_remove('delete_history_data'); // callback function in xcrud/functions.php

	echo $xcrud->render();

?>

<?php
	if (@$_GET['mode'] == "print") : //พิมพ์รายงาน (ติดบอร์ด)
		include("report-bhikkhu.php");
	endif;
?>
<script src="../../vendors/input-mask/jquery.inputmask.js"></script>
<script src="../../vendors/input-mask/jquery.inputmask.date.extensions.js"></script>
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
	// double click ที่แถวแล้วโดดไปที่หน้าแก้ไขข้อมูล
//	$(".xcrud").on("dblclick","tr.xcrud-row",function() {
//		var primary = $(this).find(".xcrud-actions a.xcrud-action:first").data("primary");
//		var container = $(this).closest(".xcrud-ajax");
//		Xcrud.request(container,Xcrud.list_data(container,{task:'edit',primary:primary}));
//	});

	// แปลงวันที่จาก mysql yyyy-mm-dd เป็น mm-dd-yyyy แสดงที่หน้า screen
//	$(document).on("xcrudafterrequest", function(event, container, data) {
//	   if(data.task == 'save' || data.task == 'create' || data.task == 'edit'){
//		   window.location = 'http://example.com';
//	   }
		// สำหรับแปลงปี คศ เป็นปี พศ ในหน้า list
//		$("#TXTordinate").inputmask({ alias: "dd.mm.yyyy"}); //static mask
//		$("#TXTbirthday").inputmask({ alias: "dd.mm.yyyy"}); //static mask
//	});

</script>