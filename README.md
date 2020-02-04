# AptPdoLibrary
How to use AptPdoLibrary functions

How to Use InsertDataWithAes function ->

Call function :

Example :

Givendata - $GivenData = "Status::::Pending::,::Username::::$Username";
DatabaseConnection - Provide PdoDatabase connection ($stmt or $dbconnection or any custom object)

DbTableName - Provide table name (Student or Staff or Any other table name)

EncodeAndEncryptPass - Provide any password witch is used to encryped data (This password also use to decryped tha data, and use string as password for perform highly secure encryption)

$Response = InsertDataWithAes($GivenData,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass);

Example :
$Response = InsertDataWithAes("Status::::Pending::,::Username::::$Username",$stmt,'tablename','password');

Check Response :
if($Response['status'] === 'Success' && $Response['code'] === 200){
  #Code... (After data insert successfully into mysql database)
}else{
  #Code...  (After data insertion failed )
}

How to Use FetchDataWithAes function ->

Call function :

$Response = FetchDataWithAes($SearchData,$RequiredData,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass,$CheckFor = 'any' ,$CheckUserStatus = NULL,$FetchCount = NULL);

Check Response :
if($Response['status'] === 'Success' && $Response['code'] === 200){
  #Code... (After data featch successfully into database)
}else if($Response['code'] === 404){
  #Code... (After Search data (Given condtion) not found in database)
}else{
  #Code...  (After data found process failed)
}

