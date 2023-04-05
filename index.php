<?php
	/* Portal Demo Version 1.0.0 */
	/*
	 * Include the following php for Open Access mode.  This includes
	 * $tnc and $login_type
	 */
	$login_type="open";
	$tnc="Service Terms and Conditions\n\n".
	"This free WiFi service (\"Service\") is provided by this company (\"Company\") to its customers. Please read below Service Terms and Conditions. To use the Service, users must accept these Service Terms and Conditions.\n\n".
	"    1. The Service allows the user to access the Internet via the Wi-Fi network provided by the Company by using the user's Wi-Fi-enabled device. In order to use the Service, the user must use a Wi-Fi -enabled device and related software. It is the user's responsibility to ensure that the user's device works with the Service.\n\n".
	"    2. The Company may from time to time modify or enhance or suspend the Service.\n\n".
	"    3. The user acknowledges and consents that:\n".
	"        (a) The Service has to be operated properly in accordance with the recommended practice, and with the appropriate hardware and software installed;\n\n".
	"        (b) The provisioning of the Service may reveal location-specific data, usage and retention of which are subject to the local standard privacy policy and jurisdiction;\n\n".
	"        (c) Every user is entitled to 20 continuous minutes free WiFi service every day at the Company's designated location(s). If the connection is disconnected within the 20 minutes due to any reason, the user cannot use the Service again on the same day;\n\n".
	"        (d) The Company excludes all liability or responsibility for any cost, claim, damage or loss to the user or to any third party whether direct or indirect of any kind including revenue, loss or profits or any consequential loss in contract, tort, under any statute or otherwise (including negligence) arising out of or in any way related to the Service (including, but not limited to, any loss to the user arising from a suspension of the Service or Wi-Fi disconnection or degrade of Service quality); and\n\n".
	"        (e) The Company will not be liable to the user or any other person for any loss or damage resulting from a delay or failure to perform these Terms and Conditions in whole or in part where such delay or failure is due to causes beyond the Company's reasonable control, or which is not occasioned by its fault or negligence, including acts or omissions of third parties (including telecommunications network operators, Information Service content providers and equipment suppliers), shortage of components, war, the threat of imminent war, riots or other acts of civil disobedience, insurrection, acts of God, restraints imposed by governments or any other supranational legal authority, industrial or trade disputes, fires, explosions, storms, floods, lightening, earthquakes and other natural calamities.\n\n".
	"    4. The user's use of the Service is subject to the coverage and connectivity conditions of the Service network and the Company makes no guarantee regarding the service performance and availability of the Service network. The Company hereby expressly reserves the right to cease the provisioning of the Service in the event the same is being substantially affected by reasons beyond the control of the Company.\n\n";
	/*
	 * Attributes included in redirection:
	 * form_action - The URL for submitting the form.
	 * ip          - The IP address of the client.
	 * client_mac  - The MAC address of the client.
	 * orig_url    - The original URL that the client accessed.
	 * name        - The name of the device.
	 * sn          - The serial number of the device.
	 * host_mac    - The MAC address of the device.
	 * host_ip     - The IP address of the device.
	 * time        - The current time of the device.
	 */
	$client_ip=$_REQUEST['ip'];
	$host_ip=$_REQUEST['host_ip'];
	$orig_url=$_REQUEST['orig_url'];
	/*
	 * Users will be redirected to $orig_url after login success.
	 * Overriding (or submitting a new) $orig_url can control the URL
	 * to be redirected to.
	 */
	// $orig_url="http://www.peplink.com";
	$form_action=(!empty($_REQUEST['form_action'])?
		$_REQUEST['form_action']:
		"https://device.pepwave.com:8000/cgi-bin/portal.cgi");
	/*
	 * The form should be submitted to the device for authentication.
	 * Please override this to the link below for Balance / Max models.
	 * This will be fixed in later releases with correct
	 * $_REQUEST['form_action'].
	 */
	$form_action="https://captive-portal.peplink.com:8000/portal.cgi";
	/*
	 * Please override this to the link below for AP models.
	 */
	// $form_action="https://device.pepwave.com:8000/cgi-bin/portal.cgi");
	$auth_code=$_REQUEST['auth_code'];
	/*
	 * Code:
	 * 200 - Login Success
	 * 401 - Invalid Username/Password
	 * 402 - Access Quota Reached
	 * 500 - Internal server Error
	 */
	$auth_msg=$_REQUEST['auth_msg'];
	$message=$_REQUEST['message'];
	$command=(!empty($_REQUEST['command'])? $_REQUEST['command']: "");
	$form_method = "POST";
	/*
	 * Form attributes:
	 * username - The username for authentication.  If "Open Access" mode
	 *            is used, this is optional.
	 * password - The password for authentication.  If "Open Access" mode
	 *            is used, this is optional.
	 * command  - To indicate the submit mode, possible values are "login",
	 *            "logout".
	 * orig_url - The URL to be redirected to after authentication success.
	*/
	if ($command == "logout")
	{
		$page_msg = "Logged Out Successfully!";
	}
	else if ($command == "popup")
	{
		/*
		 * Submitting command="logout" will logout the client.
		 */
		$page_msg = "Thank you for using Portal service!";
		$command_value = "logout";
		$submit_value = "Logout";
	}
	else
	{
		if ($_REQUEST['type'] == "login")
		{
			if ($_REQUEST['status'] == "fail")
			{
				$page_msg = "Login Failed!";
				$require_login = true;
				$command_value = "login";
				$submit_value = "Login";
			}
			else
			{
				// Success
				$page_msg = "";
				$require_continue = true;
				$require_popup = true;
				$form_method = "GET";
				$submit_value = "Click to continue";
			}
		}
		else
		{
			// Normal Redirection
			$page_msg = "Welcome to Portal!";
			$command_value = "login";
			if ($login_type == "open")
			{
				$require_tnc = true;
				$submit_value = "Agree";
			}
			else
			{
				$require_login = true;
				$submit_value = "Login";
			}
		}
	}
?>
<!DOCTYPE HTML>
<html>
<head><title>Portal</title></head>
<style type="text/css">
body {
        margin:0;
        font-family:Arial, Helvetica, sans-serif;
        font-size:14px;
}
#top_row {
        height:89px;
}
#box {
        float:left;
        height:240px;
        width:265px;
        border:4px solid #CCCCCC;
        padding:10px;
}
#title {
        font-family:Verdana, Arial, Helvetica, sans-serif;
        font-weight:700;
        font-size:16px;
}
a {
        text-decoration:none;
        color:#000000;
}
a:hover {
        text-decoration:underline;
}
.warntext
{
        color: #ff0000;
        font-weight: bold;
}
.message {
        font-family:Verdana, Arial, Helvetica, sans-serif;
}
textarea {
        font-size:12px;
}
</style>
<body> <br> <br> <br> <br> <br> <br>
<?php
if ($require_continue) {
	echo "<form method='$form_method' action='$orig_url'>";
} else {
	echo "<form method='$form_method' action='$form_action'>";
}
?>
<div align='center' style="text-align:center">
<table border="0" cellpadding="4" cellspacing="4" width="100%">
<tr><td align='center' colspan='2'><br><img src="logo.png"></td></tr>
<tr><td class='title' colspan='2'><span><?php echo "$page_msg"?></span></td></tr>
<?php
if (!empty($message)) echo "<tr><td class='title' colspan='2'><span>$message</span></td></tr>";
if (!empty($auth_msg)) echo "<tr><td class='title' colspan='2'><span>$auth_msg</span></td></tr>";
if (!empty($client_ip)) echo "<tr><td colspan='2' class='row_header'>IP: $client_ip</font></td></tr>";
if ($require_tnc)
{
echo "<tr><td class='tnc' colspan='2'><textarea cols='60' rows='10' readonly>$tnc</textarea></td></tr>";
}
if ($require_login)
{
echo "<tr><td class='row_header'>Username: </td><td><input name='username' size='32'></td></tr>";
echo "<tr><td class='row_header'>Password: </td><td><input type='password' name='password' size='32'></td></tr>";
}
if (!$require_continue && !empty($orig_url)) echo "<input type='hidden' name='orig_url' value='$orig_url'>";
if (!empty($command_value)) echo "<input type='hidden' name='command' value='$command_value'>\n";
if (!empty($submit_value)) echo "<tr><td align='center' colspan='2'><input type='submit' value='$submit_value'></td></tr>";
?>
</table>
</div>
</form>
<script>
<?php
if ($require_popup) echo "window.open('portal_redirect.php?command=popup&orig_url=$orig_url&form_action=$form_action', '', 'toolbar=0,scrollbars=0,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=375');"
?>
</script>
</body></html>

