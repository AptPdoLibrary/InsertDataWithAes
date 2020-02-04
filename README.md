# AptPdoLibrary
How to use AptPdoLibrary functions

How to Use InsertDataWithAes function ->

Call function :

$Response = InsertDataWithAes($GivenData,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass);

Check Response :
if($Response['status'] === 'Success' && $Response['code'] === 200){
  #Code... (After data insert successfully into mysql database)
}else{
  #Code...  (After data insertion failed )
}

How to Use FetchDataWithAes function ->

Call function :

$Response = FetchDataWithAes($GivenData,$Conditions,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass,$CheckFor = 'all');

Check Response :
if($Response['status'] === 'Success' && $Response['code'] === 200){
  #Code... (After data updated successfully into database)
}else{
  #Code...  (After data updation failed )
}
