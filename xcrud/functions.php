<?php
function publish_action($xcrud)
{
    if ($xcrud->get('primary'))
    {
        $db = Xcrud_db::get_instance();
        $query = 'UPDATE base_fields SET `bool` = b\'1\' WHERE id = ' . (int)$xcrud->get('primary');
        $db->query($query);
    }
}
function unpublish_action($xcrud)
{
    if ($xcrud->get('primary'))
    {
        $db = Xcrud_db::get_instance();
        $query = 'UPDATE base_fields SET `bool` = b\'0\' WHERE id = ' . (int)$xcrud->get('primary');
        $db->query($query);
    }
}

function exception_example($postdata, $primary, $xcrud)
{
    // get random field from $postdata
    $postdata_prepared = array_keys($postdata->to_array());
    shuffle($postdata_prepared);
    $random_field = array_shift($postdata_prepared);
    // set error message
    $xcrud->set_exception($random_field, 'This is a test error', 'error');
}

function test_column_callback($value, $fieldname, $primary, $row, $xcrud)
{
    return $value . ' - nice!';
}

function after_upload_example($field, $file_name, $file_path, $params, $xcrud)
{
    $ext = trim(strtolower(strrchr($file_name, '.')), '.');
    if ($ext != 'pdf' && $field == 'uploads.simple_upload')
    {
        unlink($file_path);
        $xcrud->set_exception('simple_upload', 'This is not PDF', 'error');
    }
}

function movetop($xcrud)
{
    if ($xcrud->get('primary') !== false)
    {
        $primary = (int)$xcrud->get('primary');
        $db = Xcrud_db::get_instance();
        $query = 'SELECT `officeCode` FROM `offices` ORDER BY `ordering`,`officeCode`';
        $db->query($query);
        $result = $db->result();
        $count = count($result);

        $sort = array();
        foreach ($result as $key => $item)
        {
            if ($item['officeCode'] == $primary && $key != 0)
            {
                array_splice($result, $key - 1, 0, array($item));
                unset($result[$key + 1]);
                break;
            }
        }

        foreach ($result as $key => $item)
        {
            $query = 'UPDATE `offices` SET `ordering` = ' . $key . ' WHERE officeCode = ' . $item['officeCode'];
            $db->query($query);
        }
    }
}
function movebottom($xcrud)
{
    if ($xcrud->get('primary') !== false)
    {
        $primary = (int)$xcrud->get('primary');
        $db = Xcrud_db::get_instance();
        $query = 'SELECT `officeCode` FROM `offices` ORDER BY `ordering`,`officeCode`';
        $db->query($query);
        $result = $db->result();
        $count = count($result);

        $sort = array();
        foreach ($result as $key => $item)
        {
            if ($item['officeCode'] == $primary && $key != $count - 1)
            {
                unset($result[$key]);
                array_splice($result, $key + 1, 0, array($item));
                break;
            }
        }

        foreach ($result as $key => $item)
        {
            $query = 'UPDATE `offices` SET `ordering` = ' . $key . ' WHERE officeCode = ' . $item['officeCode'];
            $db->query($query);
        }
    }
}

function show_description($value, $fieldname, $primary_key, $row, $xcrud)
{
    $result = '';
    if ($value == '1')
    {
        $result = '<i class="fa fa-check" />' . 'OK';
    }
    elseif ($value == '2')
    {
        $result = '<i class="fa fa-circle-o" />' . 'Pending';
    }
    return $result;
}

function custom_field($value, $fieldname, $primary_key, $row, $xcrud)
{
    return '<input type="text" readonly class="xcrud-input" name="' . $xcrud->fieldname_encode($fieldname) . '" value="' . $value .
        '" />';
}
function unset_val($postdata)
{
    $postdata->del('Paid');
}

function format_phone($new_phone)
{
    $new_phone = preg_replace("/[^0-9]/", "", $new_phone);

    if (strlen($new_phone) == 7)
        return preg_replace("/([0-9]{3})([0-9]{4})/", "$1-$2", $new_phone);
    elseif (strlen($new_phone) == 10)
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/", "($1) $2-$3", $new_phone);
    else
        return $new_phone;
}

function before_list_example($list, $xcrud)
{
    var_dump($list);
}

function after_update_test($pd, $pm, $xc)
{
    $xc->search = 0;
}

function after_upload_test($field, &$filename, $file_path, $upload_config, $this)
{
    $filename = 'bla-bla-bla';
}

//***************************************************************************************************
//*                                         CUSTOM FUNCTION                                         *
//***************************************************************************************************

function render_array_dropdown($table, $id, $desc) {

	$db = Xcrud_db::get_instance(); // ถ้ามี operation ที่ต้องทำกับ db ให้สร้าง instance db ขึ้นมาก่อน
	$query = "SELECT {$id}, {$desc} FROM {$table} ORDER BY {$id}";
	$db->query($query);
	$result = $db->result();

	$arr_data[''] = '';
	foreach ($result as $key => $item) {
		$arr_data[$item[$id]] = $item[$desc];
	}

	return $arr_data;

}

// call from bhikku.php -> $xcrud->before_insert
//function update_field($postdata, $xcrud) {
//	$postdata->set('ordinate', '2008-01-01');
//}

// call from bhikku.php -> $xcrud->before_insert
function validate_bhikku_data($postdata, $xcrud) {

//	if (empty($postdata->get('position_id'))) {
//		$xcrud->set_exception('position_id', 'กรุณาระบุตำแหน่ง', 'error');
//		return;
//	}
//
//	if (empty($postdata->get('name'))) {
//		$xcrud->set_exception('name', 'กรุณาระบุชื่อ', 'error');
//		return;
//	}
//
//	if (empty($postdata->get('surname'))) {
//		$xcrud->set_exception('surname', 'กรุณาระบุนามสกุล', 'error');
//		return;
//	}
//
//	if (empty($postdata->get('nickname'))) {
//		$xcrud->set_exception('nickname', 'กรุณาระบุชื่อเล่น', 'error');
//		return;
//	}
//
//	if (empty($postdata->get('alias'))) {
//		$xcrud->set_exception('alias', 'กรุณาระบุฉายา', 'error');
//		return;
//	}
//
//	if (empty($postdata->get('alias_meaning'))) {
//		$xcrud->set_exception('alias_meaning', 'กรุณาระบุความหมายฉายา', 'error');
//		return;
//	}
//
//	if (empty($postdata->get('position_extra_id'))) {
//		$xcrud->set_exception('position_extra_id', 'กรุณาระบุตำแหน่งพิเศษ', 'error');
//		return;
//	}
//
//	if ($postdata->get('ordinate') == '0000-00-00') {
//		$xcrud->set_exception('ordinate', 'กรุณาระบุวันอุปสมบท', 'error');
//		return;
//	}
//
//	if (empty($postdata->get('kuti'))) {
//		$xcrud->set_exception('kuti', 'กรุณาระบุกุฎิ', 'error');
//		return;
//	}
//
//	if ($postdata->get('birthday') == '0000-00-00') {
//		$xcrud->set_exception('birthday', 'กรุณาระบุวันเกิด', 'error');
//		return;
//	}
//
//	if (empty($postdata->get('address'))) {
//		$xcrud->set_exception('address', 'กรุณาระบุที่อยู่', 'error');
//		return;
//	}
//
//	if (empty($postdata->get('bhikkhu_opinion'))) {
//		$xcrud->set_exception('bhikkhu_opinion', 'กรุณาระบุความเห็นหมู่คณะต่อพฤติกรรม', 'error');
//		return;
//	}
//
//	if (empty($postdata->get('status_id'))) {
//		$xcrud->set_exception('status_id', 'กรุณาระบุสถานะ', 'error');
//		return;
//	}

}

// call from bhikku.php -> $xcrud->before_insert, $xcrud->before_update;
function format_date_value($postdata, $primary, $xcrud) {

	// แปลงรูปแบบตัวแปรวันที่ ให้อยู่ในรุปแบบที่จะส่งเข้า mysql (หน้าเว็บรับมาเป็น dd.mm.YYYY)
	$input_date = $postdata->get('ordinate');
	$arr_intput_date = explode('.', $input_date);

	// รูปแบบตัวแปรวันที่ที่ mysql รับคือ YYYY-mm-dd
	$year = $arr_intput_date[2] - 543; // หน้าเว็บรับปีมาเป็นปี พศ ต้องทำให้เป็นปี คศ
	$update_value = $year . '-' . $arr_intput_date[1] . '-' . $arr_intput_date[0];

	$postdata->set('ordinate', $update_value); // update ค่าที่เปลี่ยน ก่อนส่งไป update ที่ db

	//----------------------------------------------------->
	$input_date = $postdata->get('birthday');
	$arr_intput_date = explode('.', $input_date);

	// รูปแบบตัวแปรวันที่ที่ mysql รับคือ YYYY-mm-dd
	$year = $arr_intput_date[2] - 543; // หน้าเว็บรับปีมาเป็นปี พศ ต้องทำให้เป็นปี คศ
	$update_value = $year . '-' . $arr_intput_date[1] . '-' . $arr_intput_date[0];

	$postdata->set('birthday', $update_value); // update ค่าที่เปลี่ยน ก่อนส่งไป update ที่ db

}

// call from bhikku.php -> $xcrud->after_insert, $xcrud->after_update;
function update_history_data($postdata, $primary, $xcrud) {

    if ($primary) { // primary key ของหน้าที่กำลัง เพิ่ม/แก้ไข

        $db = Xcrud_db::get_instance(); // ถ้ามี operation ที่ต้องทำกับ db ให้สร้าง instance db ขึ้นมาก่อน

		// ตรวจสอบสถานะล่าสุดใน db ว่าเป็นอะไร เพื่อเทียบกับหน้า screen ที่ส่งข้อมูลเข้ามา ถ้าเหมือนกันไม่ต้อง update history status
		$query = "SELECT * FROM tbl_history WHERE bhikkhu_id = " . (int)$primary . " ORDER BY history_id DESC LIMIT 1";
		$db->query($query);
		$row = $db->row();

		if ($row['status_id'] <> $postdata->get('status_id')) {
			$query = "INSERT INTO tbl_history (bhikkhu_id, status_id, start_date) VALUES (".(int)$primary.", ".(int)$postdata->get('status_id').", '".date("Y-m-d")."')";
			$db->query($query);
		}

//		$query = "SELECT * FROM tbl_history WHERE bhikkhu_id = ".$primary." ORDER BY history_id DESC LIMIT 1";
//		$db->query($query);

		//----> กรณี query ออกมาแล้วได้แถวเดียว
//		$row = $db->row(); // ถ้ารู้ว่าออกมา 1 แถวแน่ ๆ ให้ใช้ $db->row()
//		if ($row) {
//			$query = "UPDATE tbl_history SET end_date = '".date("Y-m-d")."' WHERE history_id = ".$row['history_id'];
//			$db->query($query);
//		}

		//----> กรณี query ออกมาแล้วได้หลายแถว
//		$rows = $db->result(); // ออกมาหลายแถวให้ใช้ $db->result()
//		$count = count($rows); // นับจำนวนแถวที่ query ออกมาได้
//		foreach ($rows as $key => $row) {
//			echo $row['history_id'];
//		}

		// คำนวณอายุและพรรษา
		$query = "SELECT * FROM tbl_bhikkhu WHERE bhikkhu_id = " . (int)$primary . " LIMIT 1";
		$db->query($query);
		//----> กรณี query ออกมาแล้วได้หลายแถว
		$row = $db->row(); // ถ้ารู้ว่าออกมา 1 แถวแน่ ๆ ให้ใช้ $db->row()
		if ($row) {
			// คำนวณพรรษา
			//!!! สามเณรไม่มีพรรษา ให้คืนค่าว่างออกไป
			$phansa_year = _calculate_phansa($row["ordinate"]);
			$query = 'UPDATE tbl_bhikkhu SET phansa_year = ' . (int)$phansa_year . ' WHERE bhikkhu_id = ' . (int)$primary;
			$db->query($query);

			// คำนวณอายุ
			$age_year = _calculate_age($row["birthday"]);
			$query = 'UPDATE tbl_bhikkhu SET age_year = ' . (int)$age_year . ' WHERE bhikkhu_id = ' . (int)$primary;
			$db->query($query);

		}

		// set ลำดับ ordering กรณีเพิ่มข้อมูลเข้ามาใหม่
		if ($row["ordering"] == NULL) {
			$query = "SELECT * FROM tbl_bhikkhu ORDER BY ordering DESC LIMIT 1";
			$db->query($query);
			//----> กรณี query ออกมาแล้วได้หลายแถว
			$row = $db->row(); // ถ้ารู้ว่าออกมา 1 แถวแน่ ๆ ให้ใช้ $db->row()
			if ($row) {
				$ordering = $row["ordering"] + 1;
				$query = 'UPDATE tbl_bhikkhu SET ordering = ' . (int)$ordering . ' WHERE bhikkhu_id = ' . (int)$primary;
				$db->query($query);
			}
		}

    }

}

// call from bhikku.php -> $xcrud->before_remove
function check_document_file($primary, $xcrud) {

	$db = Xcrud_db::get_instance(); // ถ้ามี operation ที่ต้องทำกับ db ให้สร้าง instance db ขึ้นมาก่อน

	$query = "SELECT * FROM tbl_document WHERE bhikkhu_id = ".(int)$primary;
	$db->query($query);

	//----> กรณี query ออกมาแล้วได้หลายแถว
	$rows = $db->result(); // ออกมาหลายแถวให้ใช้ $db->result()
	$count = count($rows); // นับจำนวนแถวที่ query ออกมาได้
	if ($count >= 1) {
		$xcrud->set_exception('', 'ไม่สามารถลบข้อมูลภิกขุนี้ได้ ให้ลบเอกสารที่เกี่ยวข้องกับภิกขุนี้ออกให้หมดก่อน', 'error');
		return;
	}

}

// call from bhikku.php -> $xcrud->after_remove
function delete_history_data($primary, $xcrud) {

	$db = Xcrud_db::get_instance(); // ถ้ามี operation ที่ต้องทำกับ db ให้สร้าง instance db ขึ้นมาก่อน

	$query = "DELETE FROM tbl_history WHERE bhikkhu_id = " .(int)$primary;
    $db->query($query);

}

// call from bhikku.php -> $xcrud->column_callback('age','calculate_age')
function calculate_age($value, $fieldname, $primary, $row, $xcrud) {

	$db = Xcrud_db::get_instance(); // ถ้ามี operation ที่ต้องทำกับ db ให้สร้าง instance db ขึ้นมาก่อน

	$query = "SELECT * FROM tbl_bhikkhu WHERE bhikkhu_id = ".(int)$primary." LIMIT 1";
	$db->query($query);

	//----> กรณี query ออกมาแล้วได้แถวเดียว
	$row = $db->row(); // ถ้ารู้ว่าออกมา 1 แถวแน่ ๆ ให้ใช้ $db->row()
	if ($row) {
		// คำนวณอายุ
		return $age_year = _calculate_age($row["birthday"]);
	}

}

// call from bhikku.php -> $xcrud->column_callback('age','calculate_age')
function calculate_phansa($value, $fieldname, $primary, $row, $xcrud) {

	$db = Xcrud_db::get_instance(); // ถ้ามี operation ที่ต้องทำกับ db ให้สร้าง instance db ขึ้นมาก่อน

	$query = "SELECT * FROM tbl_bhikkhu WHERE bhikkhu_id = ".(int)$primary." LIMIT 1";
	$db->query($query);

	//----> กรณี query ออกมาแล้วได้แถวเดียว
	$row = $db->row(); // ถ้ารู้ว่าออกมา 1 แถวแน่ ๆ ให้ใช้ $db->row()
	if ($row) {
		// คำนวณพรรษา
		//!!! สามเณรไม่มีพรรษา ให้คืนค่าว่างออกไป
//		$phansa_year = _calculate_phansa($row["ordinate"]);
//
//		if ($row["phansa_year"] != $phansa_year) { // ถ้าพรรษาที่คำนวณกับที่พรรษาที่เก็บใน db ไม่เท่ากัน ให้ update ค่าพรรษาที่คำนวณเข้า db
//			$query = 'UPDATE tbl_bhikkhu SET phansa_year = ' . (int)$phansa_year . ' WHERE bhikkhu_id = ' . (int)$primary;
//			$db->query($query);
//		}

		return $phansa_year;
	}

}

// call from bhikku.php -> $xcrud->create_action() and $xcrud->button()
function ubosot_action($xcrud) {

	if ($xcrud->get('primary')) {

		$db = Xcrud_db::get_instance();

		$query = 'SELECT * FROM tbl_bhikkhu WHERE bhikkhu_id =' . $xcrud->get('primary') . ' LIMIT 1';
		$db->query($query);
		//----> กรณี query ออกมาแล้วได้แถวเดียว
		$row = $db->row(); // ถ้ารู้ว่าออกมา 1 แถวแน่ ๆ ให้ใช้ $db->row()
		if ($row) {
			if ($row['offence'] == 'มี') {
				$xcrud->set_exception('', 'ไม่สามารถเพิ่มรายชื่อเพื่อลงอุโบสถได้เนื่องจาก อาบัติหนัก', 'error');
				return;
			}
			if ($row['status_id'] == 2) { // ย้ายไปอยู่ที่อื่น
				$xcrud->set_exception('', 'ไม่สามารถเพิ่มรายชื่อเพื่อลงอุโบสถได้เนื่องจาก ย้ายไปอยู่ที่อื่น', 'error');
				return;
			}
			if ($row['status_id'] == 3) { // ลาสิกขา
				$xcrud->set_exception('', 'ไม่สามารถเพิ่มรายชื่อเพื่อลงอุโบสถได้เนื่องจาก ลาสิกขา', 'error');
				return;
			}
			if ($row['status_id'] == 4) { // มรณภาพ
				$xcrud->set_exception('', 'ไม่สามารถเพิ่มรายชื่อเพื่อลงอุโบสถได้เนื่องจาก มรณภาพ', 'error');
				return;
			}
		}

		$query = 'SELECT * FROM tbl_temp_ubosot WHERE bhikkhu_id =' . $xcrud->get('primary') . ' LIMIT 1';
		$db->query($query);
		//----> กรณี query ออกมาแล้วได้แถวเดียว
		$row = $db->row(); // ถ้ารู้ว่าออกมา 1 แถวแน่ ๆ ให้ใช้ $db->row()
		if ($row)
		{
			$xcrud->set_exception('', 'รายชื่อนี้ถูกเพิ่มเข้าไปเพื่อลงอุโบสถแล้ว', 'error');
			return;
		}

		$query = 'INSERT INTO tbl_temp_ubosot (bhikkhu_id) VALUES (' . $xcrud->get('primary') . ')';
		$db->query($query);
		$xcrud->set_exception('', 'เพิ่มรายชื่อเพื่อลงอุโบสถ เรียบร้อยแล้ว', 'success');
	}

}

// call from ubosot.php -> $xcrud->replace_remove()
function delete_data_tbl_temp_ubosot($primary_key, $xcrud){

	$db = Xcrud_db::get_instance();
	$query = 'DELETE FROM tbl_temp_ubosot WHERE bhikkhu_id=' . (int)$primary_key;
    $db->query($query);

}

function callback_format_date_column($value, $fieldname, $primary, $row, $xcrud) {

	$value = format_date($value, 'D2');
    return $value;

}

function format_date_before_edit($row_data, $primary, $xcrud) {

	// แปลงรูปแบบตัวแปรวันที่ของ mysql ให้อยู่ในรุปแบบที่จะแสดงในหน้า screen
	$output_date = $row_data->get('ordinate');
	$arr_outtput_date = explode('-', $output_date);

	$year = $arr_outtput_date[0] + 543; // หน้าเว็บรับปีมาเป็นปี คศ ต้องทำให้เป็นปี พศ
	$output_date = $arr_outtput_date[2] . '.' . $arr_outtput_date[1] . '.' . $year;

	$row_data->set('ordinate', $output_date); // update ค่าไปที่ screen input ค่านี้จะยังไม่มีการ update ลง db ถ้ายังไม่ได้กดปุ่ม save

	//------------------------------------------------------>
	$output_date = $row_data->get('birthday');
	$arr_outtput_date = explode('-', $output_date);

	$year = $arr_outtput_date[0] + 543; // หน้าเว็บรับปีมาเป็นปี คศ ต้องทำให้เป็นปี พศ
	$output_date = $arr_outtput_date[2] . '.' . $arr_outtput_date[1] . '.' . $year;

	$row_data->set('birthday', $output_date); // update ค่าไปที่ screen input

}

function movetop_bhikkhu($xcrud)
{
    if ($xcrud->get('primary') !== false)
    {
        $db = Xcrud_db::get_instance();

        $primary = (int)$xcrud->get('primary');
        $query = 'SELECT `bhikkhu_id` FROM `tbl_bhikkhu` ORDER BY `ordering`,`bhikkhu_id`';
        $db->query($query);

        $result = $db->result();
        $count = count($result);

        $sort = array();
        foreach ($result as $key => $item)
        {
            if ($item['bhikkhu_id'] == $primary && $key != 0)
            {
                array_splice($result, $key - 1, 0, array($item));
                unset($result[$key + 1]);
                break;
            }
        }

        foreach ($result as $key => $item)
        {
            $query = 'UPDATE `tbl_bhikkhu` SET `ordering` = ' . $key . ' WHERE bhikkhu_id = ' . $item['bhikkhu_id'];
            $db->query($query);
        }
    }
}

function movebottom_bhikkhu($xcrud)
{
    if ($xcrud->get('primary') !== false)
    {
        $db = Xcrud_db::get_instance();

        $primary = (int)$xcrud->get('primary');
        $query = 'SELECT `bhikkhu_id` FROM `tbl_bhikkhu` ORDER BY `ordering`,`bhikkhu_id`';
        $db->query($query);

        $result = $db->result();
        $count = count($result);

        $sort = array();
        foreach ($result as $key => $item)
        {
            if ($item['bhikkhu_id'] == $primary && $key != $count - 1)
            {
                unset($result[$key]);
                array_splice($result, $key + 1, 0, array($item));
                break;
            }
        }

        foreach ($result as $key => $item)
        {
            $query = 'UPDATE `tbl_bhikkhu` SET `ordering` = ' . $key . ' WHERE bhikkhu_id = ' . $item['bhikkhu_id'];
            $db->query($query);
        }
    }
}

//***************************************************************************************************
//*                                 CUSTOM PRIVATE FUNCTION                                         *
//***************************************************************************************************

function _calculate_age($birthdate) {

	$db = Xcrud_db::get_instance(); // ถ้ามี operation ที่ต้องทำกับ db ให้สร้าง instance db ขึ้นมาก่อน

	$today = date('Y-m-d');

	list($byear, $bmonth, $bday) = explode('-', $birthdate);
	list($tyear, $tmonth, $tday) = explode('-', $today);

	if ($byear < 1970) {
		$yearad = 1970 - $byear;
		$byear = 1970;
	} else {
		$yearad = 0;
	}

	$mbirth = mktime(0, 0, 0, $bmonth,$bday,$byear);
	$mtoday = mktime(0, 0, 0, $tmonth,$tday,$tyear);

	$mage = ($mtoday - $mbirth);
	$wyear = (date('Y', $mage) - 1970 + $yearad);
	$wmonth = (date('m', $mage) - 1);
	$wday = (date('d', $mage) - 1);

	$ystr = ($wyear > 1 ? " Years" : " Year");
	$mstr = ($wmonth > 1 ? " Months" : " Month");
	$dstr = ($wday > 1 ? " Days" : " Days");

	if ($wyear > 0 && $wmonth > 0 && $wday > 0) {
		$agestr = $wyear.$ystr." ".$wmonth.$mstr." ".$wday.$dstr;
	} elseif ($wyear == 0 && $wmonth == 0 && $wday > 0) {
		$agestr = $wday.$dstr;
	} elseif ($wyear > 0 && $wmonth > 0 && $wday == 0) {
		$agestr = $wyear.$ystr." ".$wmonth.$mstr;
	} elseif ($wyear == 0 && $wmonth > 0 && $wday > 0) {
		$agestr = $wmonth.$mstr." ".$wday.$dstr;
	} elseif ($wyear > 0 && $wmonth == 0 && $wday > 0) {
		$agestr = $wyear.$ystr." ".$wday.$dstr;
	} elseif ($wyear == 0 && $wmonth > 0 && $wday == 0) {
		$agestr = $wmonth.$mstr;
	} else {
		$agestr = "";
	}

	//return $agestr;
	return $wyear;

}

function _calculate_phansa($ordinate) {

	date_default_timezone_set('Asia/Bangkok');

	$db = Xcrud_db::get_instance(); // ถ้ามี operation ที่ต้องทำกับ db ให้สร้าง instance db ขึ้นมาก่อน

	$lent_in_first_date = '';
	$lent_out_first_date = '';
	$lent_in_last_obj = '';
	$lent_out_last_obj = '';

	$lent_in_first_ordinate_date = '';
	$lent_out_first_ordinate_date = '';
	$lent_in_last_ordinate_date = '';
	$lent_out_last_ordinate_date = '';

//-----> ดึงข้อมูลพรรษาของปีปัจจุบัน
	$today = date('Y-m-d');
	$today_year = date('Y', strtotime($today));

	$query = "SELECT * FROM tbl_lent WHERE lent_year = ". (int)$today_year ." LIMIT 1";
	$db->query($query);

	//----> กรณี query ออกมาแล้วได้แถวเดียว
	$row = $db->row(); // ถ้ารู้ว่าออกมา 1 แถวแน่ ๆ ให้ใช้ $db->row()
	if ($row) {
		// วันเข้าพรรษาแรกของปีปัจจุบัน
		$lent_in_first_date = date_create($row["lent_in_first"]);
		$lent_out_first_date = date_create($row["lent_out_first"]);

		// วันเข้าพรรษาหลังของปีปัจจุบัน
		$lent_in_last_obj = date_create($row["lent_in_last"]);
		$lent_out_last_obj = date_create($row["lent_out_last"]);
	}

//-----> ดึงข้อมูลพรรษาของปีที่อุปสมบท
	$ordinate_year = date('Y', strtotime($ordinate));

	$query = "SELECT * FROM tbl_lent WHERE lent_year = ". (int)$ordinate_year ." LIMIT 1";
	$db->query($query);

	//----> กรณี query ออกมาแล้วได้แถวเดียว
	$row = $db->row(); // ถ้ารู้ว่าออกมา 1 แถวแน่ ๆ ให้ใช้ $db->row()
	if ($row) {
		// วันเข้าพรรษาแรกของปีที่อุปสมบท
		$lent_in_first_ordinate_date = date_create($row["lent_in_first"]);
		$lent_out_first_ordinate_date = date_create($row["lent_out_first"]);

		// วันเข้าพรรษาหลังของปีที่อุปสมบท
		$lent_in_last_ordinate_date = date_create($row["lent_in_last"]);
		$lent_out_last_ordinate_date = date_create($row["lent_out_last"]);
	} else {
		$ordinate_year = $ordinate_year + 543;
		return 'กรุณากำหนดวันเข้าพรรษาปี ' . $ordinate_year;
	}

//------>
	$today_date = date_create($today); // เพื่อนำวันมาเปรียบเทียบกัน
	$ordinate_date = date_create($ordinate); // เพื่อนำวันมาเปรียบเทียบกัน

//------> คำนวณพรรษา
// วิธีการคำนวณ ถ้าอุปสมบทไม่ทันวันเข้าพรรษาแรก ไม่นับพรรษา รอดูต่อไปว่าอยู่ถึงวันเข้าพรรษาหลังหรือไม่ ถ้าอยู่ถึง ให้นับ 1 พรรษา ถ้าอยู่ไม่ถึง ไม่นับพรรษา
//             ถ้าอุปสมบทก่อนวันเข้าพรรษาแรกหรือเท่ากับวันเข้าพรรษาแรก นับ 1 พรรษา ถึงจะอยู่จนเลยวันเข้าพรรษาหลังก็จะไม่นับพรรษาแล้ว เพราะจะนับพรรษาได้ปีละครั้ง
//             ถ้าอุปสมบทไม่ทันวันเข้าพรรษาหลัง ไม่นับพรรษา รอดูต่อไปว่าอยู่ถึงวันเข้าพรรษาแรกของปีถัดไปหรือไม่ ถ้าอยู่ถึง ให้นับ 1 พรรษา ถ้าอยู่ไม่ถึง ไม่นับพรรษา
//
//
	// เอาปีล่าสุด - ปีที่อุปสมบท
	$phansa_year = $today_year - $ordinate_year;

	//----> ตรวจสอบวันที่อุปสมบทว่า อยู่ก่อนวันเข้าพรรษาแรกของปีที่อุปสมบทหรือไม่
	if ( $ordinate_date <= $lent_in_first_ordinate_date ) {
		// ถ้าน้อยกว่าหรือเท่ากับ ไม่ต้องทำอะไร
	} else {
		// ถ้ามากกว่า ตรวจสอบวันที่อุปสมบทว่า อยู่ก่อนวันเข้าพรรษาหลังของปีที่อุปสมบทหรือไม่
		if ( $ordinate_date <= $lent_in_last_ordinate_date ) {
			// ถ้าน้อยกว่าหรือเท่ากับ ไม่ต้องทำอะไร
		} else {
			// ถ้ามากกว่าลบปีพรรษาออก 1
			$phansa_year = $phansa_year - 1;
		}
	}

	return $phansa_year;

}

function _thai_digit($num) {
    return str_replace(array( '0' , '1' , '2' , '3' , '4' , '5' , '6' ,'7' , '8' , '9' ), array( "o" , "๑" , "๒" , "๓" , "๔" , "๕" , "๖" , "๗" , "๘" , "๙" ), $num);
}

function _thai_month($num) {
    return str_replace(array( '1' , '2' , '3' , '4' , '5' , '6' ,'7' , '8' , '9', '10', '11', '12' ), array( "มกราคม" , "กุมภาพันธ์" , "มีนาคม" , "เมษายน" , "พฤษภาคม" , "มิถุนายน" , "กรกฎาคม" , "สิงหาคม" , "กันยายน" , "ตุลาคม", "พฤศจิกายน", "ธันวาคม" ), $num);
}