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

Example :-

$SearchData = "Username::::$Username::,::UserId::::$UserId"; (It is use for condtion like Where Username equal to $Username, If Given column not available in table then it return an error)

$RequiredData = 'Name::::Class::::Gander'; (It is use to which get Data from table. If Given column not available in table then it return an error)

DatabaseConnection :-
$DatabaseConnection is a pdo connection object

DbTableName :-
$DbTableName = 'TableName';

EncodeAndEncryptPass :-
$EncodeAndEncryptPass = 'password';
It is use to decrypt the data. It must be equal to which is used to encrypt data at insert time 

CheckFor :-
$CheckFor = 'any';
any :- It indicate if any one conditoion($SearchData) satisfied then function return data

all :- It indicate if all conditoion($SearchData) satisfied then function return data

NotEqualAny :- It indicate if any one conditoion($SearchData) not satisfied then function return data

NotEqualAll :- It indicate if all conditoion($SearchData) not satisfied then function return data

StartLikeAny :- It indicate if any condtion value match with entire/staring value of given column then it return data

LikeLastAny :- It indicate if any condtion value match with entire/last value of given column then it return data

StartLikeLastAny :- It indicate if any condtion value match with entire/starting/last/middle value of given column then it return data

CheckUserStatus :-
$CheckUserStatus = 'Active or Pending or Deleted or Any other custum value';
Active - If CheckUserStatus is Active then it check Status column value must be equal Active. If there is no Status column then it return error. If table can not conation Status column then use Null ($CheckUserStatus = NULL)

Pending - If CheckUserStatus is Pending then it check Status column value must be equal Pending. If there is no Status column then it return error. If table can not conation Status column then use Null ($CheckUserStatus = NULL)

FetchCount :-
$FetchCount = 'all';
By default FetchCount  is NULL. If FetchCount is null then it return only one row in reasult

all - Then it return all rows which satisfied the given condtion($SearchData)

$Response = FetchDataWithAes($SearchData,$RequiredData,$DatabaseConnection,$DbTableName,$EncodeAndEncryptPass,$CheckFor = 'any' ,$CheckUserStatus = NULL,$FetchCount = NULL);

Check Response :
if($Response['status'] === 'Success' && $Response['code'] === 200){
  #Code... (After data featch successfully into database)
}else if($Response['code'] === 404){
  #Code... (After Search data (Given condtion) not found in database)
}else{
  #Code...  (After data found process failed)
}

