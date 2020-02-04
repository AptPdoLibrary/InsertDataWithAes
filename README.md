# AptPdoLibrary
How to use AptPdoLibrary functions

How to Use InsertDataWithAes function ->

Call function :

Example :

Required args :-
Givendata - $GivenData = "Status::::Pending::,::Username::::$Username";

DatabaseConnection - Provide PdoDatabase connection ($stmt or $dbconnection or any custom object)

DbTableName - Provide table name (Student or Staff or Any other table name)

EncodeAndEncryptPass - Provide any password witch is used to encryped data (This password also use to decryped the data, and use string as password for perform highly secure encryption)

Optional args :-
NullValueSupport - $NullValueSupport = true (By default it is false, false indicate user can not insert any null value into database, means you not proved null values in given data. true indicate user can insert null values into database, Now user can use null value in given data)


$Response = InsertDataWithAes($GivenData,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass);

Or

$Response = InsertDataWithAes($GivenData,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass,$NullValueSupport=true);

Example :
$Response = InsertDataWithAes("Status::::Pending::,::Username::::$Username",$stmt,'tablename','password');

Or

$Response = InsertDataWithAes("Status::::Pending::,::Username::::$Username",$stmt,'tablename','password',true);

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

