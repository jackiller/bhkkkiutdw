<?php
/*
 |™*******************************************************************************************™*
 | Custom Function
 |™*******************************************************************************************™*
*/
/*
 |----------------------------------------------------------------------------------------------
 | Prevent SQL Injection
 |----------------------------------------------------------------------------------------------
*/
function inputSafe($elementVariable) {

	$elementVariable = htmlspecialchars(trim($elementVariable), ENT_QUOTES);

	return $elementVariable;

}

/*
 |----------------------------------------------------------------------------------------------
 | Validate Input
 |----------------------------------------------------------------------------------------------
*/
function validateElement($ruleElement) {

	// initialized variables
	$errorFieldName = "";
	$errorMessage = "";
	$errorMessageJSON = "";
	$errorFlag = FALSE;
	$value = array();
	$systemErrors = array();
	$customErrors = array();

	// create validate object
	$objValidator = new Validate();

	// validate element
	foreach ($ruleElement as $elementName => $elementRule) {
		foreach ($elementRule as $rule => $value) {
			switch ($rule) {
				case "checkNotEmpty":
					$objValidator->checkNotEmpty($elementName, $value . " - กรุณากรอกข้อมูล");
					break;

				case "checkNotSelect":
					$objValidator->checkNotSelect($elementName, $value . " - กรุณาเลือกข้อมูล");
					break;

				case "checkIsAlphabetic":
					$objValidator->checkIsAlphabetic($elementName, $value . " - กรุณากรอกข้อมูลเป็นตัวอักษร A-Z หรือ a-z เท่านั้น");
					break;

				case "checkIsAlphanum":
					$objValidator->checkIsAlphanum($elementName, $value . " - กรุณากรอกข้อมูลเป็นตัวอักษร A-Z หรือ a-z หรือ 0-9) เท่านั้น");
					break;

				case "checkIsDigit":
					$objValidator->checkIsDigit($elementName, $value . " - กรุณากรอกตัวเลข");
					break;

				case "checkIsDigitAllowDot":
					$objValidator->checkIsDigitAllowDot($elementName, $value . " - กรุณากรอกตัวเลข หรือ .");
					break;

				case "checkLength":
					$splitValue = explode(":", $value);
					$objValidator->checkLength($elementName, $splitValue[0], $splitValue[1], $splitValue[2] . " - ข้อมูลต้องมี " . $splitValue[0] . " - " . $splitValue[1] . " หลักเท่านั้น");
					break;

				case "checkIsValidEmail":
					$objValidator->checkIsValidEmail($elementName, $value . " - รูปแบบอีเมลผิด");
					break;

				case "checkIsTelephone":
					$objValidator->checkIsTelephone($elementName, $value . " - รูปแบบโทรศัพท์ผิด");
					break;

				case "checkIdentical":
					$splitValue = explode(":", $value);
					$objValidator->checkIdentical($elementName, $splitValue[0], $splitValue[1] . " - ข้อมูลที่กรอกมาไม่ตรงกับรหัสผ่าน");
					break;

				case "checkIdenticalWithVariable":
					$splitValue = explode(":", $value);
					$objValidator->checkIdenticalWithVariable($elementName, $splitValue[0], $splitValue[1] . " - ข้อมูลที่กรอกมาไม่ตรงกัน");
					break;

				case "checkCardID":
					$objValidator->checkCardID($elementName, $value . " - หมายเลขบัตรประชาชนไม่ถูกต้อง");
					break;

				case "checkIsSmallerThan":
					$splitValue = explode(":", $value);
					$objValidator->checkIsSmallerThan($elementName, $splitValue[0], $splitValue[1] . " - กรุณากรอกข้อมูล < " . $splitValue[0]);
					break;

				case "checkIsBiggerThan":
					$splitValue = explode(":", $value);
					$objValidator->checkIsBiggerThan($elementName, $splitValue[0], $splitValue[1] . " - กรุณากรอกข้อมูล > หรือ = " . ($splitValue[0] + 1));
					break;

				case "checkIsOfValues":
					$splitValue = explode(":", $value);
					$white_list = explode("|", $splitValue[0]);
					$objValidator->checkIsOfValues($elementName, $white_list, $splitValue[1] . " - กรุณากรอกข้อมูลให้ถูกต้อง");
					break;

			}
		}
	}

	// show system error message
	$systemErrors = $objValidator->getErrors();

	if (!empty($systemErrors)) {
		foreach ($systemErrors as $fieldName => $systemInfo) {
			foreach ($systemInfo as $value) {
				$errorFieldName = $fieldName; // return field name
				$errorFlag = TRUE;
				break;
			}
			break;
		}
	}

	// show custom error message
	$customErrors = $objValidator->getMyErrors();

	if (!empty($customErrors)) {
		foreach ($customErrors as $customError) {
			$errorMessage = $customError;
			$errorFlag = TRUE;
			break;
		}
	}

	// return error message
	if ($errorFlag === TRUE) {
		//$errorMessageJSON = '{"' . $errorFieldName . '":"' . $errorMessage . '"}';
		//$errorMessageJSON = json_encode(array($errorFieldName => $errorMessage));
		$errorMessageJSON = json_encode(array(0 => $errorFieldName, 1 => FALSE, 2 => $errorMessage));
	}

	return $errorMessageJSON;

}

/*
 |----------------------------------------------------------------------------------------------
 | Validate Input
 |----------------------------------------------------------------------------------------------
*/
function validateElementGroup($ruleElement, $index) {

	// initialized variables
	$errorFieldName = "";
	$errorMessage = "";
	$errorMessageJSON = "";
	$errorFlag = FALSE;
	$value = array();
	$systemErrors = array();
	$customErrors = array();

	// create validate object
	$objValidator = new Validate();

	// validate element
	foreach ($ruleElement as $elementName => $elementRule) {
		foreach ($elementRule[$index] as $rule => $value) {
			switch ($rule) {
				case "checkNotEmptyGroup":
					$objValidator->checkNotEmptyGroup($elementName, $index, $value . " - กรุณากรอกข้อมูล");
					break;

				case "checkNotSelectGroup":
					$objValidator->checkNotSelectGroup($elementName, $index, $value . " - กรุณาเลือกข้อมูล");
					break;

				case "checkIsAlphabeticGroup":
					$objValidator->checkIsAlphabeticGroup($elementName, $index, $value . " - กรุณากรอกข้อมูลเป็นตัวอักษร A-Z หรือ a-z เท่านั้น");
					break;

				case "checkIsAlphanumGroup":
					$objValidator->checkIsAlphanumGroup($elementName, $index, $value . " - กรุณากรอกข้อมูลเป็นตัวอักษร A-Z หรือ a-z หรือ 0-9) เท่านั้น");
					break;

				case "checkIsDigitGroup":
					$objValidator->checkIsDigitGroup($elementName, $index, $value . " - กรุณากรอกตัวเลข");
					break;

				case "checkIsDigitAllowDotGroup":
					$objValidator->checkIsDigitAllowDotGroup($elementName, $index, $value . " - กรุณากรอกตัวเลข หรือ .");
					break;

				case "checkLengthGroup":
					$splitValue = explode(":", $value);
					$objValidator->checkLengthGroup($elementName, $index, $splitValue[0], $splitValue[1], $splitValue[2] . " - ข้อมูลต้องมี " . $splitValue[0] . " - " . $splitValue[1] . " หลักเท่านั้น");
					break;

				case "checkIsValidEmailGroup":
					$objValidator->checkIsValidEmailGroup($elementName, $index, $value . " - รูปแบบอีเมลผิด");
					break;

				case "checkIsTelephoneGroup":
					$objValidator->checkIsTelephoneGroup($elementName, $index, $value . " - รูปแบบโทรศัพท์ผิด");
					break;

				case "checkIdenticalGroup":
					$splitValue = explode(":", $value);
					$objValidator->checkIdenticalGroup($elementName, $index, $splitValue[0], $splitValue[1] . " - ข้อมูลที่กรอกมาไม่ตรงกับรหัสผ่าน");
					break;

				case "checkIdenticalWithVariableGroup":
					$splitValue = explode(":", $value);
					$objValidator->checkIdenticalWithVariableGroup($elementName, $index, $splitValue[0], $splitValue[1] . " - ข้อมูลที่กรอกมาไม่ตรงกัน");
					break;

				case "checkCardIDGroup":
					$objValidator->checkCardIDGroup($elementName, $index, $value . " - หมายเลขบัตรประชาชนไม่ถูกต้อง");
					break;

				case "checkIsSmallerThanGroup":
					$splitValue = explode(":", $value);
					$objValidator->checkIsSmallerThanGroup($elementName, $index, $splitValue[0], $splitValue[1] . " - กรุณากรอกข้อมูล < " . $splitValue[0]);
					break;

				case "checkIsBiggerThanGroup":
					$splitValue = explode(":", $value);
					$objValidator->checkIsBiggerThanGroup($elementName, $index, $splitValue[0], $splitValue[1] . " - กรุณากรอกข้อมูล > หรือ = " . ($splitValue[0] + 1));
					break;

				case "checkIsOfValuesGroup":
					$splitValue = explode(":", $value);
					$white_list = explode("|", $splitValue[0]);
					$objValidator->checkIsOfValuesGroup($elementName, $index, $white_list, $splitValue[1] . " - กรุณากรอกข้อมูลให้ถูกต้อง");
					break;

			}
		}
	}

	// show system error message
	$systemErrors = $objValidator->getErrors();

	if (!empty($systemErrors)) {
		foreach ($systemErrors as $fieldName => $systemInfo) {
			foreach ($systemInfo as $value) {
				if ($index != 0) {
					$fieldName = $fieldName . $index; // use with jquery cloneya plugin
				}
				$errorFieldName = $fieldName; // return field name
				$errorFlag = TRUE;
				break;
			}
			break;
		}
	}

	// show custom error message
	$customErrors = $objValidator->getMyErrors();

	if (!empty($customErrors)) {
		foreach ($customErrors as $customError) {
			$errorMessage = $customError;
			$errorFlag = TRUE;
			break;
		}
	}

	// return error message
	if ($errorFlag === TRUE) {
		//$errorMessageJSON = '{"' . $errorFieldName . '":"' . $errorMessage . '"}';
		//$errorMessageJSON = json_encode(array($errorFieldName => $errorMessage));
		$errorMessageJSON = json_encode(array(0 => $errorFieldName, 1 => FALSE, 2 => $errorMessage));
	}

	return $errorMessageJSON;

}

/*
 |----------------------------------------------------------------------------------------------
 | Check Invalid SQL Command
 |----------------------------------------------------------------------------------------------
*/
function checkSQLCommand($objConn, $sqlCommand) {

	if (!$objConn->Execute($sqlCommand)) {
		$objConn->Execute(ROLLBACK);
		echo json_encode(array("", false, SQL_EXCEPTION . ', line ' . $sqlCommand));
		exit();
	}

}

/*
 |----------------------------------------------------------------------------------------------
 | Format Date
 |----------------------------------------------------------------------------------------------
*/
function format_date($mysql_timestamp, $type) {

	$timestamp = strtotime($mysql_timestamp); // convert to timestamp

	if ($type == "DT1") {
		$format_date = date("d M y H:i:s", $timestamp);
	} elseif ($type == "D1") {
		$format_date = date("d/m/Y", $timestamp);
	} elseif ($type == "T1") {
		$format_date = date("H:i:s", $timestamp);
	} elseif ($type == "D2") {
		$format_date = date("d.m.Y", $timestamp);
		$date_arr = explode('.', $format_date);
		$thai_year = $date_arr[2] + 543;
		$format_date = $date_arr[0] . "." . $date_arr[1] . "." . $thai_year;
	} else {
		$format_date = $timestamp;
	}

	return $format_date;

}

/*
 |----------------------------------------------------------------------------------------------
 | Format Date Thai
 |----------------------------------------------------------------------------------------------
*/
function format_date_thai($mysql_timestamp) {

	$thai_date_return = '';
	global $thai_day_arr, $thai_month_arr;

	$timestamp = strtotime($mysql_timestamp); // convert to timestamp

	$thai_day_arr = array("อาทิตย์","จันทร์","อังคาร","พุธ","พฤหัสบดี","ศุกร์","เสาร์");
//	$thai_month_full_arr = array("0"=>"", "1"=>"มกราคม", "2"=>"กุมภาพันธ์", "3"=>"มีนาคม", "4"=>"เมษายน", "5"=>"พฤษภาคม", "6"=>"มิถุนายน", "7"=>"กรกฎาคม", "8"=>"สิงหาคม", "9"=>"กันยายน", "10"=>"ตุลาคม", "11"=>"พฤศจิกายน", "12"=>"ธันวาคม");
	$thai_month_arr = array("0"=>"", "1"=>"ม.ค.", "2"=>"ก.พ.", "3"=>"มี.ค.", "4"=>"เม.ย.", "5"=>"พ.ค.", "6"=>"มิ.ย.", "7"=>"ก.ค.", "8"=>"ส.ค.", "9"=>"ก.ย.", "10"=>"ต.ค.", "11"=>"พ.ย.", "12"=>"ธ.ค.");

//    $thai_date_return = "วัน" . $thai_day_arr[date("w", $timestamp)];
//    $thai_date_return .= "ที่ " . date("j", $timestamp);
//    $thai_date_return .= " เดือน" . $thai_month_arr[date("n", $timestamp)];
//    $thai_date_return .= "พ.ศ." . (date("Yํ", $timestamp) + 543);
//    $thai_date_return .= "  ".date("H:i", $timestamp) . " น.";

    $thai_date_return .= date("d", $timestamp);
    $thai_date_return .= " " . $thai_month_arr[date("n", $timestamp)];
    $thai_date_return .= " " . (date("Y", $timestamp) + 543);

    return $thai_date_return;

}

/*
 |™*******************************************************************************************™*
*/
