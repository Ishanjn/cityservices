<?php

include_once ('connConfig.php');
//error_reporting(E_ALL); [Already enable by using php.ini file]
//set_error_handler("customError");

if (isset($_SERVER["REQUEST_METHOD"])) {
    $isValid = false;
    $requestType = $_SERVER["REQUEST_METHOD"];
    $requestedFunc = "";

    //-- [Start] Validate Request
    if ((!isset($_GET['action']) && !empty($_GET['action'])) || (!isset($_POST['action']) && !empty($_POST['action']))) {
        $isValid = true;
        if (!isset($_GET['action']) && !empty($_GET['action'])) {
            $requestedFunc = trim($_GET['action']);
        } else {
            $requestedFunc = trim($_POST['action']);
        }
    } else {
        $isValid = false;
        echo json_encode(array('Success' => 0, 'Message' => "Invalid Request", 'detail' => "There are not action or function available with Request."));
        return;
    }
    //-- [End] Validate Request
    //-- [Start] Calling Desired function
    if ($isValid) {
        try {
            switch ($requestedFunc) {
                case 'signup' :
                    $out_put = signUp();
                    echo json_encode($out_put);
                    break;
                default :
                    echo json_encode(array('Success' => 0, 'Message' => "Invalid Action", 'detail' => "There are not action or function define in API with name : " . $requestedFunc));
            }
        } catch (Exception $ex) {
            //--$ex->getMessage(); //— Gets the Exception message
            //--$ex->getPrevious(); //— Returns previous Exception
            //--$ex->getCode(); //— Gets the Exception code
            //--$ex->getFile(); //— Gets the file in which the exception occurred
            //--$ex->getLine(); //— Gets the line in which the exception occurred
            //--$ex->getTrace(); //— Gets the stack trace
            //--$ex->getTraceAsString(); //— Gets the stack trace as a string
            $err = "Error occurred on File: '" . $ex->getFile() . "'; LineNo. : " . $ex->getLine() . "; Trace: " . $ex->getTraceAsString();
            echo json_encode(array('Success' => 0, 'Message' => $ex->getMessage(), 'detail' => $err));
        }
    }
    //-- [End] Calling Desired function
}

function signUp() {
    try {
        $arrOutput = array();

        $src = isset($_POST['src']) && !empty($_POST['src']) ? trim($_POST['src']) : "";
        $name = isset($_POST['name']) && !empty($_POST['name']) ? trim($_POST['name']) : "";
        if (!empty($src)) {
            if ($src == "FB") {
                $fb_Id = trim($_POST['FBId']);
                $fb_Token = trim($_POST['FBToken']);
                $gPlus_Id = "";
                $gPlus_Token = "";
            } else if ($src == "GPLUS") {
                $gPlus_Id = trim($_POST['GPlusId']);
                $gPlus_Token = trim($_POST['GPlusToken']);
                $fb_Id = "";
                $fb_Token = "";
            }

            if ($src == "FB") {
                $checkUser = "SELECT * FROM tblusers WHERE FBToken = " . $fb_Token . " AND FBId = " . $fb_Id;
            } else if ($src == "GPLUS") {
                $checkUser = "SELECT * FROM tblusers WHERE GPToken = " . $gPlus_Token . " AND GPId = " . $gPlus_Id;
            }

            if ($checkUser) {
                $row_cnt = mysqli_num_rows($checkUser);

                if ($row_cnt == 0) {
                    //---- Inser new User
                    //$strQuery = "INSERT INTO  tblusers (`Name`, `UserName`, `Password`, `IsActive`, `FacebookId`, `FacebookToken`, `GoogleId`, `GoogleToken`, `CreatedOn`, `LastModifiedOn`) VALUES (1, " . $name . ", " . $userName . ", " . $userPass . ", 0, " . $fb_Id . ", " . $fb_Token . ", " . $gPlus_Id . ", " . $gPlus_Token . ", " . NOW() . ", " . NOW() . ")";
                    $strQuery = "INSERT INTO  `cityservices`.`tblusers` (`Service_Provider` ,`Service_type` ,`Name` ,`FBToken` ,`FBId` ,`GPToken` ,`GPId`, `IsActive`, `IsOnline`, `IsVerified` ,`CreatedOn` ,`LastModifiedOn`) VALUES (0, ''," . $name . ",  " . $fb_Token . ",  " . $fb_Id . ",  " . $gPlus_Token . ",  " . $gPlus_Id . ", 0, 0, 0, " . NOW() . ",  " . NOW() . ")";

                    $insertResult = mysqli_query($con, $strQuery) or die(mysqli_error($con));
                    if ($insertResult) {
                        $arrOutput = array("success" => "1", "message" => "User successfully inserted into Database with ID:" . mysqli_insert_id($con));
                    } else {
                        $arrOutput = array("success" => "0", "message" => "There are some error.");
                    }
                } else {
                    //---- User already exist

                    $UserIsActive = $checkUser->fetch_object()->IsActive;
                    
                    // If user is Active then login
                    // else show the screen to fill info email, address, mobile number
                }
            } else {
                $arrOutput = array("success" => "0", "message" => "There are some error.");
            }
        }
    } catch (Exception $ex) {
        $err = "Error occurred on File: '" . $ex->getFile() . "'; LineNo. : " . $ex->getLine() . "; Trace: " . $ex->getTraceAsString();
        echo json_encode(array('Success' => 0, 'Message' => $ex->getMessage(), 'detail' => $err));
    }
    return "";
}

//-- [Start] Defining functions
function customerlogin() {
    return "";
}

function ownerlogin() {
    return "";
}

function customersignup() {
    return "";
}

function ownersignup() {
    try {
        $arrOutput = array();

        $name = $_POST['name'];

        $userName = "";
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            $userName = trim($_POST['email']);
        } else {
            $userName = trim($_POST['phone']) . "@onlinerestra.com";
        }

        $userPass = md5("password");

        $fb_Id = isset($_POST['FBId']) && !empty($_POST['FBId']) ? trim($_POST['FBId']) : "";
        $fb_Token = isset($_POST['FBToken']) && !empty($_POST['FBToken']) ? trim($_POST['FBToken']) : "";
        $gPlus_Id = isset($_POST['GPlusId']) && !empty($_POST['GPlusId']) ? trim($_POST['GPlusId']) : "";
        $gPlus_Token = isset($_POST['GPlusToken']) && !empty($_POST['GPlusToken']) ? trim($_POST['GPlusToken']) : "";

        $strQuery = "INSERT INTO  tblusers (`IsRestaurantOwner`, `Name`, `UserName`, `Password`, `IsActive`, `FacebookId`, `FacebookToken`, `GoogleId`, `GoogleToken`, `CreatedOn`, `LastModifiedOn`) VALUES (1, " . $name . ", " . $userName . ", " . $userPass . ", 0, " . $fb_Id . ", " . $fb_Token . ", " . $gPlus_Id . ", " . $gPlus_Token . ", " . NOW() . ", " . NOW() . ")";

        $insertResult = mysqli_query($con, $strQuery) or die(mysqli_error($con));
        if ($insertResult) {
            $arrOutput = array("success" => "1", "message" => "Restaurant owner successfully inserted into Database with ID:" . mysqli_insert_id($con));
        } else {
            $arrOutput = array("success" => "0", "message" => "There are some error.");
        }
    } catch (Exception $ex) {
        $err = "Error occurred on File: '" . $ex->getFile() . "'; LineNo. : " . $ex->getLine() . "; Trace: " . $ex->getTraceAsString();
        echo json_encode(array('Success' => 0, 'Message' => $ex->getMessage(), 'detail' => $err));
    }
    return "";
}

//    function customError($errno, $errstr) 
//    {
//        echo "<b>Error:</b> [$errno] $errstr<br>";
//        echo "Webmaster has been notified";
//        error_log("Error: [$errno] $errstr");
//    }
//-- [End] Defining functions
?>
