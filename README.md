# AptLibrary
How to use AptPhpLibrary functions

How to Use MysqliInsertGivenDataWithAes function ->

$Response = MysqliInsertGivenDataWithAes($GivenData,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass);
Check Response -> 
if($Response['status'] === 'Success' && $Response['code'] === 200){
  #Code... (After data insert successfully into mysql database)
}else{
  #Code...  (After data insertion failed )
}
