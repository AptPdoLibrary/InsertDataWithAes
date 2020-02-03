<?php
	/*

	*@Filename - InsertDataWithAes.php
	*@Des - We can insert data in mysql Database with Aes encryption using this function it return response after insert data into datanbase or return error if any error occur during data insert into mysql database
	*@Author - Arpit sharma
	*/

		function InsertDataWithAes($GivenData,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass,$NullValueSupport=false){
			if(strlen($GivenData) == 0){
				return ["status"=>"Error","msg"=>"Given Data must required"]; exit();
			}
			
			$GivenDataArray = explode("::,::",$GivenData);
			$StmtGivenDataKey = array(); 
			$GivenDataOptions = array(); 
			$StmtGivenDataVal = array();
			$i = 0;
			foreach ($GivenDataArray as $value) {
				$i++;
				
				if(strpos($value, "::::") != false || $NullValueSupport=true){
				   $TmpGivenDataArray = explode("::::",$value);
				} else{
				   return ["status"=>"Error","msg"=>"Invalid formate detect of given data","code"=>400]; exit();
				}
				
				if(preg_replace("/[^A-Za-z0-9_]/","",$TmpGivenDataArray[0]) !== ""){
					if(preg_replace("/^[ ]/","",$TmpGivenDataArray[1]) != "" || $NullValueSupport === true){
						if($i === 1){
							$StmtGivenDataPreparedKey = $TmpGivenDataArray[0];
							$StmtGivenDataPreparedVal = 'AES_ENCRYPT(:'.$TmpGivenDataArray[0].', :EncodeAndEncryptPass)';
						}else{
							$StmtGivenDataPreparedKey = $StmtGivenDataPreparedKey.', '.$TmpGivenDataArray[0];
							$StmtGivenDataPreparedVal = $StmtGivenDataPreparedVal.', AES_ENCRYPT(:'.$TmpGivenDataArray[0].', :EncodeAndEncryptPass)';
						}
						array_push($StmtGivenDataKey, $TmpGivenDataArray[0]);
						array_push($StmtGivenDataVal, $TmpGivenDataArray[1]);
					}
				}else{
					return ["status"=>"Error","msg"=>"Null value found in given data","code"=>400];
				}
			}

			if(isset($DatabaseConnection) && isset($DbTableName) && isset($StmtGivenDataPreparedKey) && isset($StmtGivenDataPreparedVal) && isset($StmtGivenDataKey) && isset($StmtGivenDataVal) && isset($EncodeAndEncryptPass)){

				// Check and remove user if account created but Status is pending
				$stmt = $DatabaseConnection->prepare("INSERT INTO $DbTableName ($StmtGivenDataPreparedKey) VALUES ($StmtGivenDataPreparedVal)");

				$stmt->bindValue(":EncodeAndEncryptPass", $EncodeAndEncryptPass, PDO::PARAM_STR);
				$i = 0;
				foreach ($StmtGivenDataVal as $value) {
					$stmt->bindValue(":".$StmtGivenDataKey[$i] , $value, PDO::PARAM_STR);
					$i++;
				}
        
				if($stmt->execute()){
					return ["status"=>"Success","msg"=>'Data Insert Successfully',"code"=>200];
				}else{
					return ["status"=>"Error","msg"=>"Data Not Insert",'reason'=>json_encode($stmt->errorinfo()),"code"=>404];
				}
			}else{
				return ["status"=>"Error","msg"=>"Some error occur! Try again later","code"=>400];
			}
		}
?>
