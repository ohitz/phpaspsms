phpaspsms
=========

The SMS class provides a simple way to create a Web-based SMS gateway
using the aspsms.com SMS service.

This is quite old code, but I still use it from time to time so this
is published here.

# Usage

In order to use the SMS class, you need to have an account at aspsms.com.  You
also need to make sure that your web server is able to access xml1.aspsms.com
and xml2.aspsms.com on ports 5061 and 5098. By default, all IP addresses are
able to access these servers. However, you can restrict the addresses that are
able to connect using your username and password on the aspsms.com web site.
Also make sure that traffic originating from your web server is not blocked by
firewalls or other filters.

Once you have your username, password and a confirmation that your IP address
is able to access the SMS gateway, our SMS class is very easy to use:

```php
require("SMS.php");

$sms = new SMS("username", "password");

$sms->setOriginator("...");
$sms->addRecipient("...");
$sms->setContent("An SMS message.");
$result = $sms->sendSMS();
if ($result != 1) {
  $error = $sms->getErrorDescription();
}
```

The constructor creates a new SMS object using your personal username and
password (received from aspsms.com). The string set by setOriginator is your
phone number or your name (since this string is not verified by the aspsms
service, you may start to mistrust the sender of messages...). You can then add
recipients' phone numbers with the addRecipient method. Note that the phone
numbers are international numbers, including the country code. The setContent
method sets the message to send (160 characters max.) and sendSMS finally sends
the message to the recipients.

An example for testing the class is provided in the file `example.php`.

# Other Features

The SMS class also offers access to the following extended features offered by
aspsms.com:

* Multiple Recipients: If you call the addRecipient method multiple times, an
  SMS will be sent to multiple recipients at once.

* Flashing SMS: Call the setFlashing method to make this a flashing SMS. Try
  this option to see what it looks like on your mobile phone.

* Blinking SMS: Call the setContent method with a second parameter that
  evaluates to true. This will cause all text inside brackets to blink. This
  option may not work on all mobile phones.

* VCard: By calling the sendVCard method, a VCard can be sent to a mobile
  phone.

* Logo: You need to set the MCC and MNC, and then call the setLogo method to
  set an URL to a 72x14 pixels BMP image. Finally, use the sendLogo method
  instead of sendSMS to send the logo. Check the aspsms documentation for more
  information.

* Credits: In order to see how many credits you have left, use the
  following code:

```php
$sms = new SMS("username", "password");
$sms->showCredits();
print $sms->getCredits();
```

* Deferred delivery can be done using the setDeferred method. The date and time
  of the delivery are given in the format ddmmyyyyhhmmss. Note that you also
  need to set the timezone using the setTimezone method if you use this
  functionality. By default, the aspsms.com servers will assume you are in the
  GMT+1 timezone:

```php
$sms->setTimezone("+4");
$sms->setDeferred("18092007140000");
```

# Delivery Status Notification

aspsms.com supports delivery status notification that lets you know if a
message has been delivered to a recipient or not. As soon as the status of a
message changes (e.g. when it is sent to the recipient), the aspsms.com server
notifies you by calling a specific URL.

In order to use delivery status notifications using PHPASPSMS, the following
needs to be done:

* A unique identifier needs to be assigned to every (SMS,Recipient) pair. You
  can pass this information to PHPASPSMS when you add a recipient:

```php
$sms->addRecipient($recipient_phonenumber, $unique_id);
```

  Note that the second argument is optional. Not setting it disables the
delivery status notification feature.

* Next, you need to provide three URLs that will be called by the aspsms.com
  server once the delivery status is known:

```php
$sms->setBufferedNotificationURL("http://domain.com/sms.php?buf=");
$sms->setDeliveryNotificationURL("http://domain.com/sms.php?ok=");
$sms->setNonDeliveryNotificationURL("http://domain.com/sms.php?no=");
```

  These URLs will be called to indicate that an SMS has been buffered,
  delivered, or that the aspsms.com server was unable to deliver it. The unique
  identifier defined using the addRecipient method will be appended to this URL.
  Your application then knows what happened to the message.

   Although this is not documented in the official documentation of the
   aspsms.com XML Interface, the following placeholders will be replaced
   inside a URL:

* <RCPNT> Recipient mobile number
* <SCTS> Service center times tamp (Submission date)
* <DSCTS> Delivery service center times tamp (Notification date)
* <RSN> Reason code
* <DST> Delivery status
* <TRN> Transaction identifier (the unique identifier specified with
  addRecipient)

For a comprehensive list of "Reason code" and "Delivery status", please refer
to the ASPSMS Documentation. Please note that if no <TRN> is given in the URL,
the unique identifier will be appended to the URL.

# Acknowledgements

   The delivery status notification feature in the PHPASPSMS script was
   sponsored by Reto Waldvogel of [4]Europlink. Thanks!

   The Nagios wrapper scripts in the /contrib directory were contributed
   by Daniel Lorch. Thanks!

   Iwan Schmid contributed the "DeferredDeliveryTime" functionality.
   Thanks!

# License

   This program is free software; you can redistribute it and/or modify it
   under the terms of the GNU General Public License as published by the
   Free Software Foundation; either version 2 of the License, or (at your
   option) any later version.

   This program is distributed in the hope that it will be useful, but
   WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
   General Public License for more details.

   You should have received a copy of the GNU General Public License along
   with this program; if not, write to the Free Software Foundation, Inc.,
   59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
