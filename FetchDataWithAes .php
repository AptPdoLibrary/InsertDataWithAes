<?php
	/*

	*@filename InsertGivenData/index.php
	*@des It return Available if Data alredy exixt
	*@Author Arpit sharma
	*/

		// Not show any error
		error_reporting(0);
		// Get server port type (exampale - http:// or https://)
		if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443) {
			$HeaderSecureType = "https://";
		}else{
			$HeaderSecureType = "http://";
		}
		// Create Domain name and save it in const variable
		define("DomainName",  $HeaderSecureType.$_SERVER['HTTP_HOST']."/Digital_gatepass");

		if(!isset($FileAccessCode) || $FileAccessCode !== 'Granted_Permisition_w65!4GsD6t*P_B&BMX2U_E9ZUuRH94GvN_xNbUH' ){
			header("Location: " . DomainName . "/PageNotAvailable/index.php");
			die();
			exit();
		}

		function FetchDataWithAes($GivenData,$Conditions,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass,$CheckFor = 'all'){
			if(strlen($GivenData) == 0){
				return ["status"=>"Error","msg"=>"Process failed! Try again later"];
				exit();
			}
			
			
			$GivenDataArray = explode("::,::",$GivenData);
			$StmtGivenDataKey = array();
			$StmtGivenDataVal = array();
			$i = 0;
			foreach ($GivenDataArray as $value) {
				$i++;
				
				if(strpos($value, "::::") !== false){
				   $TmpGivenDataArray = explode("::::",$value);
				} else{
				   return ["status"=>"Error","msg"=>"Process failed! Try again later","code"=>400]; exit();
				}
				
				if(preg_replace("/[^A-Za-z0-9_]/","",$TmpGivenDataArray[0]) !== ""){
					if(preg_replace("/^[ ]/","",$TmpGivenDataArray[1]) !== ""){
						if($i === 1){
							$UpdateString = $TmpGivenDataArray[0] . ' = AES_ENCRYPT(:'.$TmpGivenDataArray[0].'Data, :EncodeAndEncryptPass)';
						}else{
							$UpdateString = $UpdateString.', '.$TmpGivenDataArray[0] . ' = AES_ENCRYPT(:'.$TmpGivenDataArray[0].'Data, :EncodeAndEncryptPass)';
						}
						array_push($StmtGivenDataKey, $TmpGivenDataArray[0].'Data');
						array_push($StmtGivenDataVal, $TmpGivenDataArray[1]);
					}
				}else{
					return ["status"=>"Error","msg"=>"Process failed! Try again later","code"=>400];
				}
			}

			$ConditionsArray = explode("::,::",$Conditions);
			$ConditionsDataKey = array();
			$ConditionsDataVal = array();
			$i = 0;
			foreach ($ConditionsArray as $value) {
				$i++;

				if(strpos($value, "::::") !== false){
				   $TmpGivenDataArray = explode("::::",$value);
				} else{
				   return ["status"=>"Error","msg"=>"Process failed! Try again later","code"=>400];
				}

				if(preg_replace("/[^A-Za-z0-9_]/","",$TmpGivenDataArray[0]) !== ""){
					if(preg_replace("/^[ ]/","",$TmpGivenDataArray[1]) !== ""){
						if($CheckFor === 'all'){
							$TempConnector = '&&';
						}else if($CheckFor === 'any'){
							$TempConnector = '||';
						}else{
							return ["status"=>"Error","msg"=>"Process failed! Try again later","code"=>400]; exit();
						}
						if($i === 1){
							$UpdateCondition = $TmpGivenDataArray[0] .' = AES_ENCRYPT(:'.$TmpGivenDataArray[0].'Cnd, :EncodeAndEncryptPass)';
						}else{
							$UpdateCondition = $UpdateCondition.' '.$TempConnector.' '.$TmpGivenDataArray[0] .' = AES_ENCRYPT(:'.$TmpGivenDataArray[0].'Cnd, :EncodeAndEncryptPass)';
						}
						array_push($ConditionsDataKey, $TmpGivenDataArray[0].'Cnd');
						array_push($ConditionsDataVal, $TmpGivenDataArray[1]);
					}
				}else{
					return ["status"=>"Error","msg"=>"Process failed! Try again later","code"=>400];
				}
			}
			if(isset($DatabaseConnection) && isset($DbTableName)){

				// Check and remove user if account created but Status is pending
				$stmt = $DatabaseConnection->prepare("UPDATE $DbTableName SET $UpdateString  WHERE $UpdateCondition");

				$stmt->bindValue(":EncodeAndEncryptPass", $EncodeAndEncryptPass, PDO::PARAM_STR);
				$i = 0;
				foreach ($StmtGivenDataVal as $value) {
					$stmt->bindValue(":".$StmtGivenDataKey[$i] , $value, PDO::PARAM_STR);
					$i++;
				}
				$i = 0;
				foreach ($ConditionsDataVal as $value) {
					$stmt->bindValue(":".$ConditionsDataKey[$i] , $value, PDO::PARAM_STR);
					$i++;
				}
				$stmt->execute();
				if($stmt->rowCount() > 0){
					return ["status"=>"Success","msg"=>'Data Update Successfully',"code"=>200];
				}else{
					return ["status"=>"Error","msg"=>"Data Not Update",'reason'=>json_encode($stmt->errorinfo()),"code"=>404];
				}
			}else{
				return ["status"=>"Error","msg"=>"Process failed! Try again later","code"=>400];
			}
		}
?>
<!--
	Use ->
	$Response = InsertGivenData($GivenData,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass);
	Check Response -> 
	if($Response['status'] === 'Success' && $Response['code'] === 200){}
-->
