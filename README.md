# AptLibrary
How to use AptMysqlPdoLibrary functions

How to Use InsertDataWithAes function ->

Call function :

$Response = InsertDataWithAes($GivenData,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass);

Check Response :
if($Response['status'] === 'Success' && $Response['code'] === 200){
  #Code... (After data insert successfully into mysql database)
}else{
  #Code...  (After data insertion failed )
}
