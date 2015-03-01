<h2>Search Location</h2>
<form method="post" action="<?= base_url(); ?>api/">
Location: <input type="text" name="location" />
<input type="hidden" name="key" value="website" />
<input type="hidden" name="signature" value="<?= sha1("websiteanotherlongandsecurekeysy23") ?>" />
<input type="submit" name="submit" value="Search" />
</form>

<h2>Get Menu</h2>
<form method="post" action="<?= base_url(); ?>api/menu/">
Takeaway ID: <input type="text" name="takeaway" />
<input type="submit" name="submit" value="Get Menu" />
</form>

<h2>Get Menu 2 - Test</h2>
<form method="post" action="<?= base_url(); ?>api/menutest/">
Takeaway ID: <input type="text" name="takeaway" />
<input type="submit" name="submit" value="Get Menu" />
</form>

<h2>Login</h2>
<form method="post" action="<?= base_url(); ?>api/api_user/login/">
Email: <input type="text" name="email" /><br/>
Password: <input type="password" name="pass" />
<input type="submit" name="submit" value="Login" />
</form>


<h2>Register</h2>
<form method="post" action="<?= base_url(); ?>api/api_user/register/">
Email: <input type="text" name="email" /><br/>
Password: <input type="password" name="pass" />
<input type="submit" name="submit" value="Register" />
</form>


<h2>Order</h2>
<form method="post" action="<?= base_url(); ?>api/order/">
Order XML: <textarea name="order" rows="4" cols="50">
<order><register><email>test</email><pass>test</pass></register><takeaway>1</takeaway><address1>kjn</address1><address2>knkj</address2><town>nkj</town><postcode>nkjn</postcode><telephone>kjn</telephone><comments></comments><delivery>2</delivery><item><id>12</id><price>4.99</price><qty>1</qty></item><item><id>13</id><price>7.99</price><qty>1</qty></item></order></textarea>
<input type="submit" name="submit" value="Register" />
</form>