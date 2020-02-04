<?php
	/*

	*@filename FetchReuiredDataByGivenData/index.php
	*@des It return data if Data exist otherwise it return error
	*@Author Arpit sharma
	*/

	function FetchDataWithAes($SearchData,$RequiredData,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass,$CheckFor = 'any' ,$CheckUserStatus = NULL,$FetchCount = NULL){
		if((strlen($SearchData) == 0 && $SearchData != 'none') || (strlen($RequiredData) == 0 && $RequiredData != NULL)){
			return ["status"=>"Error","msg"=>"Search Data or Required Data not found","code"=>400];
		}
		
		if($SearchData != 'none'){
			// Get search data in array
			$SearchDataArray = explode("::,::",$SearchData);
			$StmtSearchKey = array();
			$StmtSearchValue = array();
			$i = 0;
			foreach ($SearchDataArray as $value) {
				$i++;
				
				if(strpos($value, "::::") !== false || $CheckFor === 'NotEqualAny' || $CheckFor === 'NotEqualAll'){
				   $TmpSearchDataArray = explode("::::",$value);
				} else{
				   return ["status"=>"Error","msg"=>"Search Data key ($value) in invalid formate","code"=>400]; exit();
				}
					
				if(preg_replace("/[^A-Za-z0-9_]/","",$TmpSearchDataArray[0]) !== "" && preg_replace("/[^A-Za-z0-9_]/","",$TmpSearchDataArray[0]) === $TmpSearchDataArray[0] && (preg_replace("/^[ ]/","",$TmpSearchDataArray[1]) !== "" || $CheckFor === 'NotEqualAny' || $CheckFor === 'NotEqualAll')){
					if($i === 1){
						if($CheckFor === 'any' || $CheckFor === 'all'){
							$StmtSearchKeyAndPreparedKey = $TmpSearchDataArray[0]." = AES_ENCRYPT(:".$TmpSearchDataArray[0]."Srh, :EncodeAndEncryptPass)";
						}else if($CheckFor === 'StartLike' || $CheckFor === 'LikeLast' || $CheckFor === 'StartLikeLast'){
							$StmtSearchKeyAndPreparedKey = "AES_DECRYPT(".$TmpSearchDataArray[0].", :EncodeAndEncryptPass) LIKE :".$TmpSearchDataArray[0].'Srh';
						}else if($CheckFor === 'NotEqualAny' || $CheckFor === 'NotEqualAll'){
							$StmtSearchKeyAndPreparedKey = $TmpSearchDataArray[0]." != AES_ENCRYPT(:".$TmpSearchDataArray[0]."Srh, :EncodeAndEncryptPass)";
						}else{
							return ["status"=>"Error","msg"=>"Invalid CheckFo detect","code"=>400];
						}
					}else{
						if($CheckFor === 'any'){
							$StmtSearchKeyAndPreparedKey = $StmtSearchKeyAndPreparedKey." || ".$TmpSearchDataArray[0]." = AES_ENCRYPT(:".$TmpSearchDataArray[0]."Srh, :EncodeAndEncryptPass)";
						}else if($CheckFor === 'all'){
							$StmtSearchKeyAndPreparedKey = $StmtSearchKeyAndPreparedKey." && ".$TmpSearchDataArray[0]." = AES_ENCRYPT(:".$TmpSearchDataArray[0]."Srh, :EncodeAndEncryptPass)";
						}else if($CheckFor === 'StartLike' || $CheckFor === 'LikeLast' || $CheckFor === 'StartLikeLast'){
							$StmtSearchKeyAndPreparedKey = $StmtSearchKeyAndPreparedKey." || AES_DECRYPT(".$TmpSearchDataArray[0].", :EncodeAndEncryptPass) LIKE :".$TmpSearchDataArray[0].'Srh';
						}else if($CheckFor === 'NotEqualAny'){
							$StmtSearchKeyAndPreparedKey = $StmtSearchKeyAndPreparedKey." || ".$TmpSearchDataArray[0]." != AES_ENCRYPT(:".$TmpSearchDataArray[0]."Srh, :EncodeAndEncryptPass)";
						}else if($CheckFor === 'NotEqualAll'){
							$StmtSearchKeyAndPreparedKey = $StmtSearchKeyAndPreparedKey." && ".$TmpSearchDataArray[0]." != AES_ENCRYPT(:".$TmpSearchDataArray[0]."Srh, :EncodeAndEncryptPass)";
						}else{
							return ["status"=>"Error","msg"=>"Invalid CheckFo detect","code"=>400];
						}
					}
					array_push($StmtSearchKey, $TmpSearchDataArray[0].'Srh');
					array_push($StmtSearchValue, $TmpSearchDataArray[1]);
				}else{
					return ["status"=>"Error","msg"=>"Search Data key (".$TmpSearchDataArray[0].") or value (".$TmpSearchDataArray[1].") contaion invalid character","code"=>400];
				}
				
			}
		}else{
			$StmtSearchKeyAndPreparedKey = '0=0';
			$StmtSearchKey = array();
			$StmtSearchValue = array();
		}

		if($RequiredData != NULL){
			// Get required data in array
			$RequiredDataArray = explode("::::",$RequiredData);
			$i = 0;
			foreach ($RequiredDataArray as $value){
				$i++;
					
				if(preg_replace("/[^A-Za-z0-9_]/","",$value) !== "" && preg_replace("/[^A-Za-z0-9_]/","",$value) === $value){
					if($i === 1){
						$StmtRequiredDataKey ="AES_DECRYPT(".$value.", :EncodeAndEncryptPass) AS ".$value;
					}else{
						$StmtRequiredDataKey = $StmtRequiredDataKey.", AES_DECRYPT(".$value.", :EncodeAndEncryptPass) AS ".$value;
					}
				}else{
					return ["status"=>"Error","msg"=>"Required Data ($value) contaion invalid character","code"=>400];
				}
			}
		}else{
			$StmtRequiredDataKey = NULL;
		}

		if(isset($DatabaseConnection) && isset($DbTableName) && (isset($StmtRequiredDataKey) || $RequiredData == NULL) && isset($StmtSearchValue) && isset($StmtSearchKey) && isset($StmtSearchKeyAndPreparedKey) && isset($EncodeAndEncryptPass)){
			if($CheckUserStatus !== NULL){
				$StmtSearchKeyAndPreparedKey = '('.$StmtSearchKeyAndPreparedKey.')  && Status = AES_ENCRYPT(:Status, :EncodeAndEncryptPass)';
			}
			if($RequiredData == NULL){
				$stmt = $DatabaseConnection->prepare("SELECT NULL FROM $DbTableName WHERE $StmtSearchKeyAndPreparedKey");
			}else{
				$stmt = $DatabaseConnection->prepare("SELECT $StmtRequiredDataKey FROM $DbTableName WHERE $StmtSearchKeyAndPreparedKey");
			}

			$stmt->bindValue(':EncodeAndEncryptPass', $EncodeAndEncryptPass, PDO::PARAM_STR);
			$i = 0;
			if($StmtSearchKeyAndPreparedKey != '0=0'){
				if($CheckFor === 'any' || $CheckFor === 'all' || $CheckFor === 'NotEqualAny' || $CheckFor === 'NotEqualAll'){
					foreach ($StmtSearchValue as $value) {
						$stmt->bindValue(':'.$StmtSearchKey[$i] , $value, PDO::PARAM_STR);
						$i++;
					}
				}else if($CheckFor === 'StartLike'){
					foreach ($StmtSearchValue as $value) {
						$stmt->bindValue(':'.$StmtSearchKey[$i] , '%'.$value, PDO::PARAM_STR);
						$i++;
					}
				}else if($CheckFor === 'LikeLast'){
					foreach ($StmtSearchValue as $value) {
						$stmt->bindValue(':'.$StmtSearchKey[$i] , $value.'%', PDO::PARAM_STR);
						$i++;
					}
				}else if($CheckFor === 'StartLikeLast'){
					foreach ($StmtSearchValue as $value) {
						$stmt->bindValue(':'.$StmtSearchKey[$i] , '%'.$value.'%', PDO::PARAM_STR);
						$i++;
					}
				}else{
					return ["status"=>"Error","msg"=>"Invalid CheckFor detect","code"=>400];
				}
			}
			if($CheckUserStatus !== NULL){
				$stmt->bindValue(':Status', $CheckUserStatus, PDO::PARAM_STR);
			}
			if($stmt->execute()){
				if($stmt->rowCount() > 0){
					if($FetchCount === NULL){
						return ['status'=>'Success','msg'=>$stmt->fetch(),'totalrows'=>$stmt->rowCount(),"code"=>200];
					}else if($FetchCount === 'all'){
						return ['status'=>'Success','msg'=>$stmt->fetchAll(),'totalrows'=>$stmt->rowCount(),"code"=>200];
					}else{
						return ["status"=>"Error","msg"=>"Invalid FetchCount detect","code"=>400];
					}
				}else{
					return ['status'=>'Error','msg'=>'Search Data (Given condtion) not found','reason'=>json_encode($stmt->errorinfo()),"code"=>404];
				}
			}else{
				return ["status"=>"Error","msg"=>"Process failed! Due to some technical error",'reason'=>json_encode($stmt->errorinfo()),"code"=>400];
			}
		}else{
			return ["status"=>"Error","msg"=>"Process failed! beacuse to some invalid data provided",'reason'=>'DatabaseConnection or Table name or search data or required data or EncodeAndEncryptPass found empty',"code"=>400];
		}
	}
?>
