<?php require_once('include/db.inc.php');
$output = array();
if(isset($_REQUEST) && is_array($_REQUEST)){
	$method = $_REQUEST['method'];
	switch ($method){
		

		case "asktheexpert" :
		
			$detail_error = 0;
			$detail_error_msg = array();
			if(!isset($_REQUEST['p_user_nm']) || empty($_REQUEST['p_user_nm'])){
				$detail_error++;
				$detail_error_msg['p_user_nm'] = 'Name filed is required';
			}elseif(!preg_match('/^[a-zA-Z0-9 ]+$/', $_REQUEST['p_user_nm'])){
				$detail_error++;
				$detail_error_msg['p_user_nm'] = 'Invalid name filed. No special character allowed.';
			}
			
			if(!isset($_REQUEST['emailid']) || empty($_REQUEST['emailid'])){
				$detail_error++;
				$detail_error_msg['emailid'] = 'Email filed is required';
			}elseif(!filter_var($_REQUEST['emailid'], FILTER_VALIDATE_EMAIL)) {
				$detail_error++;
				$detail_error_msg['emailid'] = 'Invalid email id';
			}
			
			if(!isset($_REQUEST['p_user_mob']) || empty($_REQUEST['p_user_mob'])){
				$detail_error++;
				$detail_error_msg['p_user_mob'] = 'Mobile filed is required';
			}elseif(!preg_match('/^[7-9]{1,1}[0-9]{9,9}$/', $_REQUEST['p_user_mob'])) {
				$detail_error++;
				$detail_error_msg['p_user_mob'] = 'Invalid mobile number';
			}
			
			
			if(!isset($_REQUEST['ud1']) || empty($_REQUEST['ud1'])){
				$detail_error++;
				$detail_error_msg['ud1'] = 'message filed is required';
			}
		
			
			if(isset($detail_error) && ($detail_error == 0)){
				$insert_user_sql = 	"INSERT INTO `protein_asktheexpert` SET ";
				$insert_user_sql .= " `name` = '" . @mysqli_real_escape_string($connection, $_REQUEST['p_user_nm']) . "'";
				$insert_user_sql .= ", `email` = '" . @mysqli_real_escape_string($connection, $_REQUEST['emailid']) . "'";
				$insert_user_sql .= ", `mobile` = '" . @mysqli_real_escape_string($connection, $_REQUEST['p_user_mob']) . "'";
				$insert_user_sql .= ", `message` = '" . @mysqli_real_escape_string($connection, $_REQUEST['ud1']) . "'";
				
				
				@mysqli_query($connection, $insert_user_sql);
				$inserted_user_id = @mysqli_insert_id($connection);
				if(intval($inserted_user_id)){
					$msg = 'Name : ' . $_REQUEST["p_user_nm"] . "<br>" ;
					$msg .= 'Email : ' . $_REQUEST["emailid"] . ' <br>'  ;
					$msg .= 'Mobile : ' . $_REQUEST["p_user_mob"] . ' <br>'  ;
					$msg .= 'Message : ' . $_REQUEST["ud1"] . ' <br>'  ;
					
					$headers = 'Bcc: sunil.limboo@indigo.co.in' . "\r\n" ;
					mail("vishal.ingale@indigo.co.in","Ask the expert",$msg);
					header("Location: http://myprotinex.com/thanks.php");
					$output['status'] 		= 'success';
					$output['code']			= '1020';
					$output['inserted_id']	= $inserted_user_id;
					$output['message'] 		= 'Data inserted successfully';
				}else{
					$output['status'] 		= 'error';
					$output['code']			= '0021';
					$output['message'] 		= 'Fields are mendatory';
					$output['filed_message'] = $detail_error_msg;
				}
			}else{
				$output['status'] 		= 'error';
				$output['code']			= '0020';
				$output['message'] 		= 'Fields are mendatory';
				$output['filed_message'] = $detail_error_msg;
			}
		break;
		
		case "protinex_bytes_leads" :
		
			$detail_error = 0;
			$detail_error_msg = array();
			if(!isset($_REQUEST['name']) || empty($_REQUEST['name'])){
				$detail_error++;
				$detail_error_msg['name'] = 'Name filed is required';
			}elseif(!preg_match('/^[a-zA-Z0-9 ]+$/', $_REQUEST['name'])){
				$detail_error++;
				$detail_error_msg['name'] = 'Invalid name filed. No special character allowed.';
			}
			
			if(!isset($_REQUEST['emailid']) || empty($_REQUEST['emailid'])){
				$detail_error++;
				$detail_error_msg['emailid'] = 'Email filed is required';
			}elseif(!filter_var($_REQUEST['emailid'], FILTER_VALIDATE_EMAIL)) {
				$detail_error++;
				$detail_error_msg['emailid'] = 'Invalid email id';
			}
			
			if(!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])){
				$detail_error++;
				$detail_error_msg['mobile'] = 'Mobile filed is required';
			}elseif(!preg_match('/^[7-9]{1,1}[0-9]{9,9}$/', $_REQUEST['mobile'])) {
				$detail_error++;
				$detail_error_msg['mobile'] = 'Invalid mobile number';
			}
			
			
			if(!isset($_REQUEST['message']) || empty($_REQUEST['message'])){
				$detail_error++;
				$detail_error_msg['message'] = 'message filed is required';
			}
		
			
			if(isset($detail_error) && ($detail_error == 0)){
				$insert_user_sql = 	"INSERT INTO `protinex_bytes_leads` SET ";
				$insert_user_sql .= " `name` = '" . @mysqli_real_escape_string($connection, $_REQUEST['name']) . "'";
				$insert_user_sql .= ", `email` = '" . @mysqli_real_escape_string($connection, $_REQUEST['emailid']) . "'";
				$insert_user_sql .= ", `mobile` = '" . @mysqli_real_escape_string($connection, $_REQUEST['mobile']) . "'";
				$insert_user_sql .= ", `message` = '" . @mysqli_real_escape_string($connection, $_REQUEST['message']) . "'";
				
				
				@mysqli_query($connection, $insert_user_sql);
				$inserted_user_id = @mysqli_insert_id($connection);
				if(intval($inserted_user_id)){
					
					$output['status'] 		= 'success';
					$output['code']			= '1020';
					$output['inserted_id']	= $inserted_user_id;
					$output['message'] 		= 'Data inserted successfully';
				}else{
					$output['status'] 		= 'error';
					$output['code']			= '0021';
					$output['message'] 		= 'Fields are mendatory';
					$output['filed_message'] = $detail_error_msg;
				}
			}else{
				$output['status'] 		= 'error';
				$output['code']			= '0020';
				$output['message'] 		= 'Fields are mendatory';
				$output['filed_message'] = $detail_error_msg;
			}
		break;
		
		case "calculator" :
			//print_r($_REQUEST);exit;
			$detail_error = 0;
			$detail_error_msg = array();
			if(!isset($_REQUEST['p_user_nm']) || empty($_REQUEST['p_user_nm'])){
				$detail_error++;
				$detail_error_msg['p_user_nm'] = 'Name filed is required';
			}elseif(!preg_match('/^[a-zA-Z0-9 ]+$/', $_REQUEST['p_user_nm'])){
				$detail_error++;
				$detail_error_msg['p_user_nm'] = 'Invalid name filed. No special character allowed.';
			}
			
			if(!isset($_REQUEST['emailid']) || empty($_REQUEST['emailid'])){
				$detail_error++;
				$detail_error_msg['emailid'] = 'Email filed is required';
			}elseif(!filter_var($_REQUEST['emailid'], FILTER_VALIDATE_EMAIL)) {
				$detail_error++;
				$detail_error_msg['emailid'] = 'Invalid email id';
			}
			
			if(!isset($_REQUEST['p_user_mob']) || empty($_REQUEST['p_user_mob'])){
				$detail_error++;
				$detail_error_msg['p_user_mob'] = 'Mobile filed is required';
			}elseif(!preg_match('/^[7-9]{1,1}[0-9]{9,9}$/', $_REQUEST['p_user_mob'])) {
				$detail_error++;
				$detail_error_msg['p_user_mob'] = 'Invalid mobile number';
			}
			
			if(!isset($_REQUEST['ud1']) || empty($_REQUEST['ud1'])){
				$detail_error++;
				$detail_error_msg['ud1'] = 'Age filed is required';
			}elseif(!preg_match('/^[0-9 ]+$/', $_REQUEST['ud1'])){
				$detail_error++;
				$detail_error_msg['ud1'] = 'Invalid age filed. No special character allowed.';
			}
			
			if(!isset($_REQUEST['ud3']) || empty($_REQUEST['ud3'])){
				$detail_error++;
				$detail_error_msg['ud3'] = 'Weight filed is required';
			}elseif(!preg_match('/^[0-9 ]+$/', $_REQUEST['ud3'])){
				$detail_error++;
				$detail_error_msg['ud3'] = 'Invalid Weight filed. No special character allowed.';
			}
			
			if(!isset($_REQUEST['udHt']) || empty($_REQUEST['udHt'])){
				$detail_error++;
				$detail_error_msg['udHt'] = 'Weight filed is required';
			}elseif(!preg_match('/^[0-9 ]+$/', $_REQUEST['udHt'])){
				$detail_error++;
				$detail_error_msg['udHt'] = 'Invalid Height filed. No special character allowed.';
			}
			
			if(!isset($_REQUEST['res_pro']) || empty($_REQUEST['res_pro'])){
				$detail_error++;
				$detail_error_msg['res_pro'] = 'Total protein is required';
			}elseif(!preg_match('/^[0-9 ]+$/', $_REQUEST['udHt'])){
				$detail_error++;
				$detail_error_msg['res_pro'] = 'Invalid Height filed. No special character allowed.';
			}
			
			if(!isset($_REQUEST['ud2']) || empty($_REQUEST['ud2'])){
				$detail_error++;
				$detail_error_msg['ud2'] = 'Name filed is required';
			}elseif(!preg_match('/^[a-zA-Z0-9 ]+$/', $_REQUEST['ud2'])){
				$detail_error++;
				$detail_error_msg['ud2'] = 'Invalid diet filed. No special character allowed.';
			}
			
			if(isset($detail_error) && ($detail_error == 0)){
				$insert_user_sql = 	"INSERT INTO `protein_calculator` SET ";
				$insert_user_sql .= " `name` = '" . @mysqli_real_escape_string($connection, $_REQUEST['p_user_nm']) . "'";
				$insert_user_sql .= ", `email` = '" . @mysqli_real_escape_string($connection, $_REQUEST['emailid']) . "'";
				$insert_user_sql .= ", `mobile_no` = '" . @mysqli_real_escape_string($connection, $_REQUEST['p_user_mob']) . "'";
				$insert_user_sql .= ", `age` = '" . @mysqli_real_escape_string($connection, $_REQUEST['ud1']) . "'";
				$insert_user_sql .= ", `weight` = '" . @mysqli_real_escape_string($connection, $_REQUEST['ud3']) . "'";
				$insert_user_sql .= ", `height` = '" . @mysqli_real_escape_string($connection, $_REQUEST['udHt']) . "'";
				$insert_user_sql .= ", `total_protein` = '" . @mysqli_real_escape_string($connection, $_REQUEST['res_pro']) . "'";
				$insert_user_sql .= ", `diet` = '" . @mysqli_real_escape_string($connection, $_REQUEST['ud2']) . "'";
				
				@mysqli_query($connection, $insert_user_sql);
				$inserted_user_id = @mysqli_insert_id($connection);
				if(intval($inserted_user_id)){
					$requestString = '';
					foreach($_REQUEST as $key=>$value) {
						$requestString .= $key. '=' . $value . '&';
					}
					
					header("Location: http://myprotinex.com/pro-cal/thanks.php?$requestString");
					$output['status'] 		= 'success';
					$output['code']			= '1020';
					$output['inserted_id']	= $inserted_user_id;
					$output['message'] 		= 'Data inserted successfully';
				}else{
					$output['status'] 		= 'error';
					$output['code']			= '0021';
					$output['message'] 		= 'Fields are mendatory';
					$output['filed_message'] = $detail_error_msg;
				}
			}else{
				$output['status'] 		= 'error';
				$output['code']			= '0020';
				$output['message'] 		= 'Fields are mendatory';
				$output['filed_message'] = $detail_error_msg;
			}
		break;
		
		case "user" :
			$detail_error = 0;
			$detail_error_msg = array();
			if(!isset($_REQUEST['name']) || empty($_REQUEST['name'])){
				$detail_error++;
				$detail_error_msg['name'] = 'Name filed is required';
			}elseif(!preg_match('/^[a-zA-Z0-9 ]+$/', $_REQUEST['name'])){
				$detail_error++;
				$detail_error_msg['name'] = 'Invalid name filed. No special character allowed.';
			}
			
			if(!isset($_REQUEST['age']) || empty($_REQUEST['age'])){
				$detail_error++;
				$detail_error_msg['age'] = 'Age filed is required';
			}elseif(!preg_match('/^[0-9 ]+$/', $_REQUEST['age'])){
				$detail_error++;
				$detail_error_msg['age'] = 'Invalid age filed. No special character allowed.';
			}
			
			
			if(!isset($_REQUEST['email']) || empty($_REQUEST['email'])){
				$detail_error++;
				$detail_error_msg['email'] = 'Email filed is required';
			}elseif(!filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL)) {
				$detail_error++;
				$detail_error_msg['email'] = 'Invalid email id';
			}
			if(!isset($_REQUEST['mobile']) || empty($_REQUEST['mobile'])){
				$detail_error++;
				$detail_error_msg['mobile'] = 'Mobile filed is required';
			}elseif(!preg_match('/^[7-9]{1,1}[0-9]{9,9}$/', $_REQUEST['mobile'])) {
				$detail_error++;
				$detail_error_msg['mobile'] = 'Invalid mobile number';
			}
			if(!isset($_REQUEST['city']) || empty($_REQUEST['city'])){
				$detail_error++;
				$detail_error_msg['city'] = 'city filed is required';
			}elseif(!preg_match('/^[a-zA-Z0-9 ]+$/', $_REQUEST['city'])){
				$detail_error++;
				$detail_error_msg['city'] = 'Invalid city name';
			}
			if(!isset($_REQUEST['state']) || empty($_REQUEST['state'])){
				$detail_error++;
				$detail_error_msg['state'] = 'city filed is required';
			}elseif(!preg_match('/^[a-zA-Z0-9 ]+$/', $_REQUEST['state'])){
				$detail_error++;
				$detail_error_msg['state'] = 'Invalid state name';
			}
			/*
			if(!isset($_REQUEST['message']) || empty($_REQUEST['message'])){
				$detail_error++;
				$detail_error_msg['message'] = 'message filed is required';
			}
		
			
			if(!isset($_REQUEST['address1']) || empty($_REQUEST['address1'])){
				$detail_error++;
				$detail_error_msg['address1'] = 'Address1 filed is required';
			}elseif(!preg_match('/^[a-zA-Z0-9 \.,-]+$/', $_REQUEST['address1'])){
				$detail_error++;
				$detail_error_msg['address1'] = 'Invalid Address1 filed. No special character allowed.';
			}
			if(!isset($_REQUEST['address2']) || empty($_REQUEST['address2'])){
			}elseif(!preg_match('/^[a-zA-Z0-9 \.,-]+$/', $_REQUEST['address2'])){
				$detail_error++;
				$detail_error_msg['address2'] = 'Invalid Address2 filed. No special character allowed.';
			}
			*/
			
			
			if(isset($detail_error) && ($detail_error == 0)){
				$insert_user_sql = 	"INSERT INTO `protinex_data` SET ";
				$insert_user_sql .= " `name` = '" . @mysqli_real_escape_string($connection, $_REQUEST['name']) . "'";
				$insert_user_sql .= ", `age_of_kid` = '" . @mysqli_real_escape_string($connection, $_REQUEST['age']) . "'";
				$insert_user_sql .= ", `emailid` = '" . @mysqli_real_escape_string($connection, $_REQUEST['email']) . "'";
				$insert_user_sql .= ", `mobileno` = '" . @mysqli_real_escape_string($connection, $_REQUEST['mobile']) . "'";
				$insert_user_sql .= ", `city` = '" . @mysqli_real_escape_string($connection, $_REQUEST['city']) . "'";
				$insert_user_sql .= ", `state` = '" . @mysqli_real_escape_string($connection, $_REQUEST['state']) . "'";
				$insert_user_sql .= ", `message` = '" . @mysqli_real_escape_string($connection, $_REQUEST['message']) . "'";
				
				
				//$insert_user_sql .= ", `status` = 1
					//					, `created` = '" . date("Y-m-d H:i:s") . "'";
				@mysqli_query($connection, $insert_user_sql);
				$inserted_user_id = @mysqli_insert_id($connection);
				if(intval($inserted_user_id)){
					$output['status'] 		= 'success';
					$output['code']			= '1020';
					$output['inserted_id']	= $inserted_user_id;
					$output['message'] 		= 'New user detail inserted successfully';
				}else{
					$output['status'] 		= 'error';
					$output['code']			= '0021';
					$output['message'] 		= 'Fields are mendatory';
					$output['filed_message'] = $detail_error_msg;
				}
			}else{
				$output['status'] 		= 'error';
				$output['code']			= '0020';
				$output['message'] 		= 'Fields are mendatory';
				$output['filed_message'] = $detail_error_msg;
			}
		break;
		
		
		case "getimages":
			if(isset($_REQUEST['page']) && !empty($_REQUEST['page'])){
				$page = $_REQUEST['page'];
				$items_per_page = 8;
				$offset = ($page - 1) * $items_per_page;
				$selectQry = "SELECT * FROM uploaddata LIMIT $offset,$items_per_page";
			} else {
				$selectQry = "SELECT * FROM uploaddata LIMIT 0,8";
			}
			$result = mysqli_query($connection, $selectQry);
			if (mysqli_num_rows($result) > 0) {
				while($row = mysqli_fetch_assoc($result)) {
					$output['result'][$row['id']] = $row['imagepath'];
				}
			}
			$output['status'] 		= 'success';
			$output['code']			= '1020';
		break;

		default :
			$output['status'] 		= 'error';
			$output['code']			= '0030';
			$output['message'] 		= 'Invalid method request';
		break;
		
		
	}
}else{
	$output['status'] 		= 'error';
	$output['code']			= '0000';
	$output['message'] 		= 'Invalid request type';
}
echo json_encode($output);