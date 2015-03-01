Find out more at [https://paul-blundell.github.io/Order-Some-Food/](https://paul-blundell.github.io/Order-Some-Food/)

# Currently Not Maintained

This application was created a long time ago and is currently not being maintained.
I hope to update this at some point in the near future, perhaps even completely
rewriting it but currently I do not have the time.

Order Some Food is being made available as is and is intended for experienced
developers to use as a starting point.

If you make updates feel free to send a pull request.

Good luck.

# Website Installation

Ensure you have a server setup correctly with PHP and MySQL installed.
The server must also have mod_rewrite and curl installed for the website to work.

1. Create a new database and import the supplied .sql file
2. Go to application/config/database.php and update the database connection details
3. Go to application/config/config.php and update the base server URL details
4. Rename _htaccess to .htaccess
5. Open .htaccess and modify RewriteBase to the base folder of the website
4. Open the website in the browser window.

Website optimised for Google Chrome or Mozilla Firefox.

There are several user accounts available, all follow the format testX@test.test with the password test
where X is a number 1 to 6.

test@test.test is a website administrator that can approve takeaways
test4@test.test is a takeaway owner with 2 takeaways associated with it

# Recommended

Once installed we recommend you change the API keys in the database and then in 
the file application/config/constants.php file. This will ensure your websites
API is secure.

You can find additional configuration settings in application/config/config.php
under the heading 'Order Some Food Configuration', here you will be able to
set your country and the websites currency.


# Website Tests

The website tests can be run by going to:

	path_to_project/tests/run_all
	
# License

Details about the license can be found in LICENSE.txt

