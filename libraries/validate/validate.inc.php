<?php
/***********************************************************************************************/
/*	@class: form validator class (&#220;)														   */
/***********************************************************************************************/
/*	@description: object for validating form input											   */
/*	@author: rudi bieller (contact@reducedcomplexity.com)								   	   */
/***********************************************************************************************/
class Validate { /* begin of class */
    /**
     *	container for error messages
     *	@access private
     *	@var array
     */
    var $errorList = array();
    /**
     *	container for occured errors
     *	@access private
     *	@var array
     */
    var $errors = array();
    /**
     *	container for userdefined error messages
     *	@access private
     *	@var array
     */
    var $errorsUserdefined = array();
    /**
     *	superglobal of request method (POST, GET)
     *	@access private
     *	@var array
     */
    var $requestMethod = array();
    /***********************************************************************************************/
    /*	@constructor																	   		   */
    /***********************************************************************************************/
    /*	@method: rc_formValidator														   		   */
    /***********************************************************************************************/
    /**
     *	constructor.
     *		automatically sets the superglobal to be used, defaults to POST
     *	@access public
     *	@desc constructor.
     *		automatically sets the superglobal to be used, defaults to POST
     *	@param string $requestMethod
     *	@param string $lang
     *	@return void
     */
    function Validate($requestMethod = "POST", $lang = "EN") {
        $this->_setRequestMethod($requestMethod);
        $this->flushErrors();
        $this->flushMyErrors();
        $this->_createErrorMessages($lang);
    }
    /***********************************************************************************************/
    /*	@input checking method															   		   */
    /***********************************************************************************************/
    /*	@method: checkNotEmpty																   	   */
    /***********************************************************************************************/
    /**
     *	check if a field is empty
     *	@access public
     *	@desc check if a field is empty
     *	@param string field
     *	@param string $msg
     *	@return bool
     */
    function checkNotEmpty($field, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is empty */
        if (!isset($this->requestMethod[$field])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        return true;
    }

    function checkNotEmptyGroup($field, $index, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is empty */
        if (!isset($this->requestMethod[$field][$index])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        return true;
    }

    /***********************************************************************************************/
    /*	@method: checkIsNumeric																   	   */
    /***********************************************************************************************/
    /**
     *	check if a field value is numeric
     *	@access public
     *	@desc check if a field value is numeric
     *	@param string field
     *	@param string $msg
     *	@return bool
     */
    function checkIsNumeric($field, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is numeric */
        if (!is_numeric($this->requestMethod[$field])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 103);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        return true;
    }

    function checkIsNumericGroup($field, $index, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is numeric */
        if (!is_numeric($this->requestMethod[$field][$index])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 103);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        return true;
    }

    /***********************************************************************************************/
    /*	@method: checkLength																   	   */
    /***********************************************************************************************/
    /**
     *	check if a field value is of a certain length (between min and max)
     *	@access public
     *	@desc check if a field value of a certain length (between min and max)
     *	@param string field
     *	@param int $min
     *	@param int $max
     *	@param string $msg
     *	@return bool
     */
    function checkLength($field, $min, $max, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is min */
        if (mb_strlen($this->requestMethod[$field], 'utf-8') < (int)$min) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 102);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is max */
        if (mb_strlen($this->requestMethod[$field], 'utf-8') > (int)$max) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 101);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        return true;
    }

    function checkLengthGroup($field, $index, $min, $max, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is min */
        if (mb_strlen($this->requestMethod[$field][$index], 'utf-8') < (int)$min) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 102);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is max */
        if (mb_strlen($this->requestMethod[$field][$index], 'utf-8') > (int)$max) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 101);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        return true;
    }

    /***********************************************************************************************/
    /*	@method: checkIdentical																   	   */
    /***********************************************************************************************/
    /**
     *	check if two fields have an identical value (case sensitive)
     *	@access public
     *	@desc check if two fields have an identical value (case sensitive)
     *	@param string field
     *	@param string field2
     *	@param string $msg
     *	@return bool
     */
    function checkIdentical($field, $field2, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        if ($this->_prepareInput($this->requestMethod[$field2]) === false) {
            $this->_setErrorMessage($field2, $this->requestMethod[$field2], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is identical */
        if ($this->requestMethod[$field] === $this->requestMethod[$field2]) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 105);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIdenticalGroup($field, $index, $field2, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        if ($this->_prepareInput($this->requestMethod[$field2][$index]) === false) {
            $this->_setErrorMessage($field2, $this->requestMethod[$field2][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is identical */
		if ($this->requestMethod[$field] === $this->requestMethod[$field2][$index]) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 105);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkIsValidEmail															   	   */
    /***********************************************************************************************/
    /**
     *	check if a given email address seems to be valid
     *	@access public
     *	@desc check if a given email address seems to be valid
     *	@param string field
     *	@param string $msg
     *	@return bool
     */
    function checkIsValidEmail($field, $msg = "") {
        /* email pattern */
        $pattern = "/^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@(([0-9a-zA-Z])+([-\w]*[0-9a-zA-Z])*\.)+[a-zA-Z]{2,9})$/";
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is valid email */
        if (preg_match($pattern, $this->requestMethod[$field])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 104);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIsValidEmailGroup($field, $index, $msg = "") {
        /* email pattern */
        $pattern = "/^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@(([0-9a-zA-Z])+([-\w]*[0-9a-zA-Z])*\.)+[a-zA-Z]{2,9})$/";
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is valid email */
        if (preg_match($pattern, $this->requestMethod[$field][$index])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 104);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkIsText																   	   */
    /***********************************************************************************************/
    /**
     *	check if input is plain text
     *	@access public
     *	@desc check if input is plain text
     *	@param string field
     *	@param string $msg
     *	@return bool
     */
    function checkIsText($field, $msg = "") {
        /* plain text pattern */
        $pattern = "/^([^\x-\x1F]|[\r\n])+$/";
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is text */
        if (preg_match($pattern, $this->requestMethod[$field])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 106);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIsTextGroup($field, $index, $msg = "") {
        /* plain text pattern */
        $pattern = "/^([^\x-\x1F]|[\r\n])+$/";
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is text */
        if (preg_match($pattern, $this->requestMethod[$field][$index])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 106);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkIsAlphabetic															   	   */
    /***********************************************************************************************/
    /**
     *	check if input is alphabetic (a-z, A-Z)
     *	@access public
     *	@desc check if input is alphabetic (a-z, A-Z)
     *	@param string field
     *	@param string $msg
     *	@return bool
     */
    function checkIsAlphabetic($field, $msg = "") {
        /* alphabetic pattern */
        $pattern = "/^[a-z]+$/i";
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is alphabetic */
        if (preg_match($pattern, $this->requestMethod[$field])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 107);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIsAlphabeticGroup($field, $index, $msg = "") {
        /* alphabetic pattern */
        $pattern = "/^[a-z]+$/i";
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is alphabetic */
        if (preg_match($pattern, $this->requestMethod[$field][$index])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 107);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkIsDigit																   	   */
    /***********************************************************************************************/
    /**
     *	check if input is a digit (0-9)
     *	@access public
     *	@desc check if input is a digit (0-9)
     *	@param string field
     *	@param string $msg
     *	@return bool
     */
    function checkIsDigit($field, $msg = "") {
        /* digit pattern */
        $pattern = "/^[0-9]+$/";
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is digit */
        if (preg_match($pattern, $this->requestMethod[$field])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 108);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIsDigitGroup($field, $index, $msg = "") {
        /* digit pattern */
        $pattern = "/^[0-9]+$/";
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is digit */
        if (preg_match($pattern, $this->requestMethod[$field][$index])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 108);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkIsAlphanum															   	   */
    /***********************************************************************************************/
    /**
     *	check if input is alphanumeric (letters, numbers)
     *	@access public
     *	@desc check if input is alphanumeric (letters, numbers)
     *	@param string field
     *	@param string $msg
     *	@return bool
     */
    function checkIsAlphanum($field, $msg = "") {
        /* alphanumeric pattern */
        $pattern = "/^[a-z0-9]+$/i";
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is alphanumeric */
        if (preg_match($pattern, $this->requestMethod[$field])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 109);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIsAlphanumGroup($field, $index, $msg = "") {
        /* alphanumeric pattern */
        $pattern = "/^[a-z0-9]+$/i";
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is alphanumeric */
        if (preg_match($pattern, $this->requestMethod[$field][$index])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 109);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkPassword																   	   */
    /***********************************************************************************************/
    /**
     *	check if two values are identical and can be used as password
     *	@access public
     *	@desc check if two values are identical and can be used as password
     *	@param string field
     *	@param string field2
     *	@param int $min
     *	@param int $max
     *	@param string $msg
     *	@return bool
     */
    function checkPassword($field, $field2, $min, $max, $msg = "") {
        /* password pattern */
        $pattern = "/^[\41-\176]+$/";
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        if (!$this->_prepareInput($this->requestMethod[$field2])) {
            $this->_setErrorMessage($field2, $this->requestMethod[$field2], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* check password */
        if (!preg_match($pattern, $this->requestMethod[$field])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 110);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        if (!$this->checkIdentical($field, $field2, $msg)) {
            return false;
        }
        if (!$this->checkLength($field, $min, $max, $msg)) {
            return false;
        }
        return true;
    }

    function checkPasswordGroup($field, $field2, $index, $min, $max, $msg = "") {
        /* password pattern */
        $pattern = "/^[\41-\176]+$/";
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        if (!$this->_prepareInput($this->requestMethod[$field2][$index])) {
            $this->_setErrorMessage($field2, $this->requestMethod[$field2][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* check password */
        if (!preg_match($pattern, $this->requestMethod[$field][$index])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 110);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        if (!$this->checkIdentical($field, $field2, $msg)) {
            return false;
        }
        if (!$this->checkLength($field, $min, $max, $msg)) {
            return false;
        }
        return true;
    }

    /***********************************************************************************************/
    /*	@method: checkIsOfValues															   	   */
    /***********************************************************************************************/
    /**
     *	check if input is of a list of valid entries
     *	@access public
     *	@desc check if input is of a list of valid entries
     *	@param string field
     *	@param array $validValues (only these values will be accepted)
     *	@param string $msg
     *	@return bool
     */
    function checkIsOfValues($field, $validValues, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is of values */
        if (!is_array($validValues)) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 201);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        if (!in_array($this->requestMethod[$field], $validValues)) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 120);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        return true;
    }

    function checkIsOfValuesGroup($field, $index, $validValues, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is of values */
        if (!is_array($validValues)) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 201);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        if (!in_array($this->requestMethod[$field][$index], $validValues)) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 120);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        return true;
    }

    /***********************************************************************************************/
    /*	@method: checkIsWithinRange															   	   */
    /***********************************************************************************************/
    /**
     *	check if input is within a range of digits (e.g. between 0 and 20)
     *	@access public
     *	@desc check if input is within a range of digits (e.g. between 0 and 20)
     *	@param string field
     *	@param int $min
     *	@param int $max
     *	@param string $msg
     *	@return bool
     */
    function checkIsWithinRange($field, $min, $max, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is within range */
        if (!is_numeric($this->requestMethod[$field])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 103);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        $this->requestMethod[$field] = intval($this->requestMethod[$field]);
        if (($this->requestMethod[$field] >= $min) && ($this->requestMethod[$field] <= $max)) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 110);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIsWithinRangeGroup($field, $index, $min, $max, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is within range */
        if (!is_numeric($this->requestMethod[$field][$index])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 103);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        $this->requestMethod[$field][$index] = intval($this->requestMethod[$field][$index]);
        if (($this->requestMethod[$field][$index] >= $min) && ($this->requestMethod[$field][$index] <= $max)) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 110);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkIsSmallerThan															   	   */
    /***********************************************************************************************/
    /**
     *	check if input is smaller than a given digit / numeric value
     *	@access public
     *	@desc check if input is smaller than a given digit / numeric value
     *	@param string field
     *	@param int $value
     *	@param string $msg
     *	@return bool
     */
    function checkIsSmallerThan($field, $value, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is smaller than */
        if (!is_numeric($this->requestMethod[$field])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 103);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        $this->requestMethod[$field] = intval($this->requestMethod[$field]);
        if ($this->requestMethod[$field] < $value) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 111);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIsSmallerThanGroup($field, $index, $value, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is smaller than */
        if (!is_numeric($this->requestMethod[$field][$index])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 103);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        $this->requestMethod[$field][$index] = intval($this->requestMethod[$field][$index]);
        if ($this->requestMethod[$field][$index] < $value) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 111);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkIsBiggerThan															   	   */
    /***********************************************************************************************/
    /**
     *	check if input is bigger than a given digit / numeric value
     *	@access public
     *	@desc check if input is bigger than a given digit / numeric value
     *	@param string field
     *	@param int $value
     *	@param string $msg
     *	@return bool
     */
    function checkIsBiggerThan($field, $value, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is bigger than */
        if (!is_numeric($this->requestMethod[$field])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 103);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        $this->requestMethod[$field] = intval($this->requestMethod[$field]);
        if ($this->requestMethod[$field] > $value) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 112);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIsBiggerThanGroup($field, $index, $value, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is bigger than */
        if (!is_numeric($this->requestMethod[$field][$index])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 103);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        $this->requestMethod[$field][$index] = intval($this->requestMethod[$field][$index]);
        if ($this->requestMethod[$field][$index] > $value) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 112);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkDateFormat															   	   */
    /***********************************************************************************************/
    /**
     *	check if input is of a given date format and valid
     *		does not check if format is e.g. 01 or 1
     *		please be careful with year format yy (e.g. 0-69 -> 2001/2069 | 70-99 -> 1970/1999)
     *	@access public
     *	@desc check if input is of a given date format and valid
     *	@param string $field
     *	@param string $format
     *	@param string $seperator
     *	@param string $msg
     *	@return bool
     */
    function checkDateFormat($field, $format, $seperator = "-", $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        $format = strtolower($format);
        /* check if given seperator is found in date format */
        if (!stristr($this->requestMethod[$field], $seperator)) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 130);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* add format like dd-mm */
        $arrValidFormats = array("yy" . $seperator . "mm" . $seperator . "dd", "yy" . $seperator . "dd" . $seperator . "mm", "yyyy" . $seperator . "mm" . $seperator . "dd", "yyyy" . $seperator . "dd" . $seperator . "mm", "mm" . $seperator . "dd" . $seperator . "yy", "mm" . $seperator . "dd" . $seperator . "yyyy", "dd" . $seperator . "mm" . $seperator . "yy", "dd" . $seperator . "mm" . $seperator . "yyyy");
        /* check if the passed date format is valid */
        if (!in_array($format, $arrValidFormats)) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 131);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* get the day/month/year and check for validity
        day depending from month and year between 1 and 28/29/30/31
        month between 1 and 12
        year between 0 and 9999
        */
        $formParts = explode($seperator, $format);
        $parts = explode($seperator, $this->requestMethod[$field]);
        $order = array();
        $year_format = "";
        /* put the parts in a specific order */
        for ($i = 0;$i < sizeof($formParts);$i++) {
            switch ($formParts[$i]) {
                case "yy":
                    $order[0] = $parts[$i];
                    $year_format = "yy";
                break;
                case "yyyy":
                    $order[0] = $parts[$i];
                    $year_format = "yyyy";
                break;
                case "mm":
                    $order[1] = $parts[$i];
                break;
                case "dd":
                    $order[2] = $parts[$i];
                break;
                default:
                break;
            }
        }
        /* check the day/month/year for validity */
        $error = false;
        if (!$this->_checkYear($order[0], $year_format)) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 134);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            $error = true;
        }
        if (!$this->_checkMonth($order[1])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 133);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            $error = true;
        }
        if (!$this->_checkDay($order[2], $order[1], $order[0], $year_format)) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 132);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            $error = true;
        }
        if ($error) {
            return false;
        }
        return true;
    }

    function checkDateFormatGroup($field, $index, $format, $seperator = "-", $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        $format = strtolower($format);
        /* check if given seperator is found in date format */
        if (!stristr($this->requestMethod[$field][$index], $seperator)) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 130);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* add format like dd-mm */
        $arrValidFormats = array("yy" . $seperator . "mm" . $seperator . "dd", "yy" . $seperator . "dd" . $seperator . "mm", "yyyy" . $seperator . "mm" . $seperator . "dd", "yyyy" . $seperator . "dd" . $seperator . "mm", "mm" . $seperator . "dd" . $seperator . "yy", "mm" . $seperator . "dd" . $seperator . "yyyy", "dd" . $seperator . "mm" . $seperator . "yy", "dd" . $seperator . "mm" . $seperator . "yyyy");
        /* check if the passed date format is valid */
        if (!in_array($format, $arrValidFormats)) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 131);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* get the day/month/year and check for validity
        day depending from month and year between 1 and 28/29/30/31
        month between 1 and 12
        year between 0 and 9999
        */
        $formParts = explode($seperator, $format);
        $parts = explode($seperator, $this->requestMethod[$field][$index]);
        $order = array();
        $year_format = "";
        /* put the parts in a specific order */
        for ($i = 0;$i < sizeof($formParts);$i++) {
            switch ($formParts[$i]) {
                case "yy":
                    $order[0] = $parts[$i];
                    $year_format = "yy";
                break;
                case "yyyy":
                    $order[0] = $parts[$i];
                    $year_format = "yyyy";
                break;
                case "mm":
                    $order[1] = $parts[$i];
                break;
                case "dd":
                    $order[2] = $parts[$i];
                break;
                default:
                break;
            }
        }
        /* check the day/month/year for validity */
        $error = false;
        if (!$this->_checkYear($order[0], $year_format)) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 134);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            $error = true;
        }
        if (!$this->_checkMonth($order[1])) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 133);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            $error = true;
        }
        if (!$this->_checkDay($order[2], $order[1], $order[0], $year_format)) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 132);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            $error = true;
        }
        if ($error) {
            return false;
        }
        return true;
    }

    /***********************************************************************************************/
    /*	@custom methods							  		   									   	   */
    /***********************************************************************************************/
    /*	@method: checkNotSelect																   	   */
    /***********************************************************************************************/
    /**
     *	check if tag <select>...</select> is selected
     *	@access public
     *	@desc check if tag <select>...</select> is selected
     *	@param string field
     *	@param string $msg
     *	@return bool
     */
    function checkNotSelect($field, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is not select */
        if ($this->requestMethod[$field] == "") {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 666);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        return true;
    }

    function checkNotSelectGroup($field, $index, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is not select */
        if ($this->requestMethod[$field][$index] == "") {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 666);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        return true;
    }

    /***********************************************************************************************/
    /*	@method: checkIsTelephone															 	   */
    /***********************************************************************************************/
    /**
     *	check if a given telephone seems to be valid
     *	@access public
     *	@desc check if a given telephone seems to be valid
     *	@param string field
     *	@param string $msg
     *	@return bool
     */
    function checkIsTelephone($field, $msg = "") {
        /* telephone pattern */
        $pattern = "/^\([0-9]{2,3}\)\s?[0-9]{3}(-|\s)?[0-9]{4}$|^[0-9]{3}-?[0-9]{3}-?[0-9]{4}$/";
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is telephone */
        if (preg_match($pattern, $this->requestMethod[$field])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 666);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIsTelephoneGroup($field, $index, $msg = "") {
        /* telephone pattern */
        $pattern = "/^\([0-9]{2,3}\)\s?[0-9]{3}(-|\s)?[0-9]{4}$|^[0-9]{3}-?[0-9]{3}-?[0-9]{4}$/";
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is telephone */
        if (preg_match($pattern, $this->requestMethod[$field][$index])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 666);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkIsURL																	 	   */
    /***********************************************************************************************/
    /**
     /*	check if a given URL seems to be valid
     *	@access public
     *	@desc check if a given URL seems to be valid
     *	@param string field
     *	@param string $msg
     *	@return bool
     */
    function checkIsURL($field, $msg = "") {
        /* url pattern */
        $pattern = "/^(ht|f)tp(s?)\:\/\/[a-zA-Z0-9\-\._]+(\.[a-zA-Z0-9\-\._]+){2,}(\/?)([a-zA-Z0-9\-\.\?\,\'\/\\\+&amp;%\$#_]*)?$/";
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is url */
        if (preg_match($pattern, $this->requestMethod[$field])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 666);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIsURLGroup($field, $index, $msg = "") {
        /* url pattern */
        $pattern = "/^(ht|f)tp(s?)\:\/\/[a-zA-Z0-9\-\._]+(\.[a-zA-Z0-9\-\._]+){2,}(\/?)([a-zA-Z0-9\-\.\?\,\'\/\\\+&amp;%\$#_]*)?$/";
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is url */
        if (preg_match($pattern, $this->requestMethod[$field][$index])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 666);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkIsDomain																	 	   */
    /***********************************************************************************************/
    /**
     /*	check if a given Domain seems to be valid
     *	@access public
     *	@desc check if a given Domain seems to be valid
     *	@param string field
     *	@param string $msg
     *	@return bool
     */
    function checkIsDomain($field, $msg = "") {
        /* domain pattern */
        $pattern = "/^([a-z0-9]([-a-z0-9]*[a-z0-9])?\.)+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]|travel)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])$/i";
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is domain */
        if (preg_match($pattern, $this->requestMethod[$field])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 666);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIsDomainGroup($field, $index, $msg = "") {
        /* domain pattern */
        $pattern = "/^([a-z0-9]([-a-z0-9]*[a-z0-9])?\.)+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]|travel)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])$/i";
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is domain */
        if (preg_match($pattern, $this->requestMethod[$field][$index])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 666);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkIdenticalWithVariable													 	   */
    /***********************************************************************************************/
    /*	@access public
     *	@desc check if one fields and one variable have an identical value (case sensitive)
     *	@param string field
     *	@param string value
     *	@param string $msg
     *	@return bool
    */
    function checkIdenticalWithVariable($field, $value, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is identical with variable */
        if ($this->requestMethod[$field] === $value) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 105);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIdenticalWithVariableGroup($field, $index, $value, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is identical with variable */
        if ($this->requestMethod[$field][$index] === $value) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 105);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkCardID																 	   */
    /***********************************************************************************************/
    /*	check if a given thai card id seems to be valid
     *
     *	@access public
     *	@desc check if a given card id seems to be valid
     *	@param string field
     *	@param string $msg
     *	@return bool
    */
    function checkCardID($field, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        if (mb_strlen($this->requestMethod[$field], 'utf-8') != 13) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            $this->errorsUserdefined[] = $msg;
            return false;
        }
        for ($i = 0, $sum = 0;$i < 12;$i++) {
            $sum+= (int)($this->requestMethod[$field]{$i}) * (13 - $i);
        }
        if ((11 - ($sum % 11)) % 10 == (int)($this->requestMethod[$field]{12})) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 202);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkCardIDGroup($field, $index, $msg = "") {
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        if (mb_strlen($this->requestMethod[$field][$index], 'utf-8') != 13) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            $this->errorsUserdefined[] = $msg;
            return false;
        }
        for ($i = 0, $sum = 0;$i < 12;$i++) {
            $sum+= (int)($this->requestMethod[$field][$index]{$i}) * (13 - $i);
        }
        if ((11 - ($sum % 11)) % 10 == (int)($this->requestMethod[$field][$index]{12})) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 202);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@method: checkIsDigitAllowDot																   	   */
    /***********************************************************************************************/
    /**
     *	check if input is a digit (0-9) or .
     *	@access public
     *	@desc check if input is a digit (0-9) or .
     *	@param string field
     *	@param string $msg
     *	@return bool
     */
    function checkIsDigitAllowDot($field, $msg = "") {
        /* digit pattern */
        $pattern = "/^[0-9.]+$/";
        if ($this->_prepareInput($this->requestMethod[$field]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is digit */
        if (preg_match($pattern, $this->requestMethod[$field])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field], 108);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    function checkIsDigitAllowDotGroup($field, $index, $msg = "") {
        /* digit pattern */
        $pattern = "/^[0-9.]+$/";
        if ($this->_prepareInput($this->requestMethod[$field][$index]) === false) {
            $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 100);
            if (!empty($msg)) {
                $this->errorsUserdefined[] = $msg;
            }
            return false;
        }
        /* is digit */
        if (preg_match($pattern, $this->requestMethod[$field][$index])) {
            return true;
        }
        $this->_setErrorMessage($field, $this->requestMethod[$field][$index], 108);
        if (!empty($msg)) {
            $this->errorsUserdefined[] = $msg;
        }
        return false;
    }

    /***********************************************************************************************/
    /*	@error handling methods																   	   */
    /***********************************************************************************************/
    /*	@method: hasErrors																	   	   */
    /***********************************************************************************************/
    /**
     *	checks if there have been any errors
     *	@access public
     *	@desc checks if there have been any errors
     *	@return array
     */
    function hasErrors() {
        if (count($this->errors) > 0) {
            return true;
        }
        return false;
    }
    /***********************************************************************************************/
    /*	@method: hasMyErrors																   	   */
    /***********************************************************************************************/
    /**
     *	checks if there have been any userdefined errors
     *	@access public
     *	@desc checks if there have been any userdefined errors
     *	@return array
     */
    function hasMyErrors() {
        if (count($this->errorsUserdefined) > 0) {
            return true;
        }
        return false;
    }
    /***********************************************************************************************/
    /*	@method: getErrors																	   	   */
    /***********************************************************************************************/
    /**
     *	returns an array with occured errors
     *	@access public
     *	@desc returns an array with occured errors
     *	@return array
     */
    function getErrors() {
        return $this->errors;
    }
    /***********************************************************************************************/
    /*	@method: getMyErrors																   	   */
    /***********************************************************************************************/
    /**
     *	returns an array with occured errors and userdefined error messages
     *	@access public
     *	@desc returns an array with occured errors
     *	@return array
     */
    function getMyErrors() {
        return $this->errorsUserdefined;
    }
    /***********************************************************************************************/
    /*	@method: flushErrors																   	   */
    /***********************************************************************************************/
    /**
     *	empties error container
     *	@access public
     *	@desc empties error container
     *	@return void
     */
    function flushErrors() {
        $this->errors = array();
    }
    /***********************************************************************************************/
    /*	@method: flushMyErrors																   	   */
    /***********************************************************************************************/
    /**
     *	empties userdefined error container
     *	@access public
     *	@desc empties error container
     *	@return void
     */
    function flushMyErrors() {
        $this->errorsUserdefined = array();
    }
    /***********************************************************************************************/
    /*	@private methods						  		   									   	   */
    /***********************************************************************************************/
    /*	@method: _setErrorMessage															   	   */
    /***********************************************************************************************/
    /**
     *	sets an error message
     *	@access private
     *	@desc sets an error message
     *	@param int $errorCode
     *	@return void
     */
    function _setErrorMessage($field, $value, $errorCode) {
        $this->errors[$field][] = array($this->_getErrorMessageFromNumber($errorCode), $value);
    }
    /***********************************************************************************************/
    /*	@method: _getErrorMessageFromNumber													   	   */
    /***********************************************************************************************/
    /**
     *	returns the error message belonging to an error code
     *	@access private
     *	@desc returns the error message belonging to an error code
     *	@param int $errorCode
     *	@return string
     */
    function _getErrorMessageFromNumber($errorCode) {
        $message = "";
        if (isset($this->errorList[$errorCode])) {
            $message = $this->errorList[$errorCode];
        } else {
            $message = "unknown error code " . $errorCode;
        }
        return $message;
    }
    /***********************************************************************************************/
    /*	@method: _createErrorMessages														   	   */
    /***********************************************************************************************/
    /**
     *	fills container with error messages according to set language
     *	@access private
     *	@desc fills container with error messages according to set language
     *	@param string $lang
     *	@return void
     */
    function _createErrorMessages($lang = "EN") {
        switch ($lang) {
            case "EN":
                $this->errorList = array(100 => "Missing Input", 101 => "Input is too big", 102 => "Input is too small", 103 => "Input must be numeric", 104 => "Input must be an email address", 105 => "The two values are not identical", 106 => "Input mus be plain text", 107 => "Input must be alphabetical (letters valid, only)", 108 => "Input must be a digit", 109 => "Input is not alphanumeric (digits and letters are valid, only)", 110 => "Input is not within valid range", 111 => "Inputvalue is too big", 112 => "Inputvalue is too small", 120 => "Input is not a valid value", 130 => "Input has no valid seperator", 131 => "Dateformat to be checked is not valid", 132 => "Input contains an invalid day", 133 => "Input contains an invalid month", 134 => "Input contains an invalid year", 201 => "Passed value is not an array", 666 => "Not define message");
            break;
        }
    }
    /***********************************************************************************************/
    /*	@method: _prepareInput																   	   */
    /***********************************************************************************************/
    /**
     *	removes whitespaces from userinput
     *	@access private
     *	@desc removes whitespaces from userinput
     *	@param string $value
     *	@return mixed (boolean false if input field is empty OR a cleaned up string whithout whitespaces and magic quotes slashes)
     */
    function _prepareInput(&$value) {
        $value = trim($value);
        if (!isset($value) || $value == "") {
            return false;
        }
        if (get_magic_quotes_gpc() == 1) {
            $value = stripslashes($value);
        }
        return $value;
    }
    /***********************************************************************************************/
    /*	@method: _setRequestMethod															   	   */
    /***********************************************************************************************/
    /**
     *	sets the superglobal for request method (POST, GET)
     *	@access private
     *	@desc sets the superglobal for request method (POST, GET)
     *	@param string $requestMethod
     *	@return void
     */
    function _setRequestMethod($requestMethod) {
        switch ($requestMethod) {
            case "POST":
                $this->requestMethod = & $_POST;
            break;
            case "GET":
                $this->requestMethod = & $_GET;
            break;
            default:
                $this->requestMethod = & $_REQUEST;
            break;
        }
    }
    /***********************************************************************************************/
    /*	@method: _checkYear																	   	   */
    /***********************************************************************************************/
    /**
     *	checks for a valid year
     *	@access private
     *	@desc checks for a valid year
     *	@param int $year
     *	@param string $year_format
     *	@return void
     */
    function _checkYear($year, $year_format) {
        if (!is_numeric($year)) {
            return false;
        }
        if ($year < 1 || $year > 2037) {
            return false;
        }
        if ($year_format == "yyyy" && (strlen(strval($year)) != 4)) {
            return false;
        }
        return true;
    }
    /***********************************************************************************************/
    /*	@method: _checkMonth																   	   */
    /***********************************************************************************************/
    /**
     *	checks for a valid month
     *	@access private
     *	@desc checks for a valid month
     *	@param int $month
     *	@return void
     */
    function _checkMonth($month) {
        if (!is_numeric($month)) {
            return false;
        }
        if ($month < 1 || $month > 12) {
            return false;
        }
        return true;
    }
    /***********************************************************************************************/
    /*	@method: _checkDay																	   	   */
    /***********************************************************************************************/
    /**
     *	checks for a valid day
     *	@access private
     *	@desc checks for a valid day
     *	@param int $day
     *	@param int $month
     *	@param int $year
     *	@param string $year_format
     *	@return void
     */
    function _checkDay($day, $month, $year, $year_format) {
        if (!is_numeric($day)) {
            return false;
        }
        if (!$this->_checkYear($year, $year_format)) {
            return false;
        }
        if (!$this->_checkMonth($month)) {
            return false;
        }
        $check = strval($year);
        if ((strlen($check) == 4) && ($year < 1970)) {
            $lastDayOfMonth = 31;
        } else {
            $lastDayOfMonth = date("j", mktime(0, 0, 0, $month + 1, 0, $year));
        }
        if ($day < 1 || $day > $lastDayOfMonth) {
            return false;
        }
        return true;
    }
    /***********************************************************************************************/
} /* end of class */