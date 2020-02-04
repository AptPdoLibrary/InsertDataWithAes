# AptPdoLibrary
How to use AptPdoLibrary functions

How to Use InsertDataWithAes function ->

Call function :

example Givendata -

$GivenData = "Status::::Pending::,::Username::::$Username";

$Response = InsertDataWithAes($GivenData,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass);

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

