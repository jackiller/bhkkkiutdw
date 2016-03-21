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
					$objValidator->checkNotEmpty($elementName, $value . " - This Field is Required");
					break;

				case "checkNotSelect":
					$objValidator->checkNotSelect($elementName, $value . " - This Field is Required");
					break;

				case "checkIsAlphabetic":
					$objValidator->checkIsAlphabetic($elementName, $value . " - Letters Only");
					break;

				case "checkIsAlphanum":
					$objValidator->checkIsAlphanum($elementName, $value . " - No Special Characters Allowed");
					break;

				case "checkIsDigit":
					$objValidator->checkIsDigit($elementName, $value . " - Numbers Only");
					break;

				case "checkIsDigitAllowDot":
					$objValidator->checkIsDigitAllowDot($elementName, $value . " - Numbers Only");
					break;

				case "checkLength":
					$splitValue = explode(":", $value);
					$objValidator->checkLength($elementName, $splitValue[0], $splitValue[1], $splitValue[2] . " - At least " . $splitValue[0] . " - " . $splitValue[1] . " digits");
					break;

				case "checkIsValidEmail":
					$objValidator->checkIsValidEmail($elementName, $value . " - Invalid Email");
					break;

				case "checkIsTelephone":
					$objValidator->checkIsTelephone($elementName, $value . " - Invalid Mobile No.");
					break;

				case "checkIdentical":
					$splitValue = explode(":", $value);
					$objValidator->checkIdentical($elementName, $splitValue[0], $splitValue[1] . " - ข้อมูลที่กรอกมาไม่ตรงกับรหัสผ่าน");
					break;

				case "checkIdenticalWithVariable":
					$splitValue = explode(":", $value);
					$objValidator->checkIdenticalWithVariable($elementName, $splitValue[0], $splitValue[1] . " - Fields do not Match");
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
		$format_date = date("d/m/Y", $timestamp);
	} else {
		$format_date = $timestamp;
	}

	return $format_date;

}

/*
 |™*******************************************************************************************™*
*/
