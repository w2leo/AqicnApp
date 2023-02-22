# TODO List

Insert Recovery token to DB.
After "get" recovery_token - search in db firsr. Don't save in $_Session

Refactor DynamoDB !!!!!
For exapmle, add flag "changed data" and only 1 request to DB

1. Login user
	+ * Encrypt password, validate encrypt password
	* Add Regex pattern for Login
	* Update for GET requests in login / password incorrect (write below input fielsds "Incorrect login/password)
	* Read about $_Session data
2. Register user
	* Check if login / email exists
		* If exists - add 'X' red after input field, if not - add 'V' green
	* All validates set in functions
			* If uncorrect - add 'X' red after input field, if correct - add 'V' green
	* Crypt password before sending to DB
	* Validate only crypted passwords (match or doesn't match password)
	* Create email validation link (add to DB)
	* Add email validation flag (default - 'false')
3. Main page:
	* Get data from DB for $_Session['login']
	* Show main city
	* Show checkbox "subscribe main"
	* Show List Registered cities
	* button "delete"
	* show input form for adding more cities
