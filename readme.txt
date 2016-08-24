To use our security library:
1) Both the library and the developer should be preferably in the same directory
2) include("injprev.php"); in the developer code
3) Then according to call 
	a) sanitize_input function by passing the connection details of localhost,username,password,database name, user input and data type as an array.
	b)pwi() function by passing the connection details as an array with details of localhost,database name, user name,password in the same order.
4) The sanitized input returned could be passed to the query for its execution.
5)The test1_with_dynamicPDO.php file contains the given test1.php modified according to our preferred PDO function .
6)The test1_with_InputSanitzation.php contains the given test1.php modified with the input sanitizations.


