# OAuth2 Server Plugin for Moodle

It provides an [OAuth2](https://tools.ietf.org/html/rfc6749 "RFC6749") server so that a user can use its Moodle account to log in to external applications.
Oauth2 Library has been taken from https://github.com/bshaffer/oauth2-server-php

## Requirements
* #### Moodle 4.5 or higher installed
* #### Admin account

## Installation steps
1. Download the plugin from Moodle plugins directory or from GitHub repository.

2. Extract the files if you downloaded a zip file.

3. Create a folder "oauth2" in the "local" directory of your Moodle installation. Copy the files from the plugin into this folder.

4. Login to the site as site administrator.

5. Go to *Site Administration > Server > OAuth2 server > Manage OAuth clients*

6. Click *Add OAuth client*

7. Fill in the form. Your Client Identifier and Client Secret (which will be given later) will be used for you to authenticate. The Redirect URL must be the URL mapping to your client that will be used.

## How to get an access token

1. From your application, redirect the user to this URL: `http://moodledomain.com/local/oauth2/login.php?client_id=EXAMPLE&response_type=code` *(remember to replace the URL domain with the domain of Moodle and replace EXAMPLE with the Client Identifier given in the form.)*

2. The user must log in to Moodle and authorize your application to use its basic info.

3. If it went all OK, the plugin should redirect the user to something like: `http://yourapplicationdomain.com/foo?code=55c057549f29c428066cbbd67ca6b17099cb1a9e` *(that's a GET request to the Redirect URI given with the code parameter)*

4. Using the code given, your application must send a POST request to `http://moodledomain.com/local/oauth2/token.php`  with the following parameters: `{'code': '55c057549f29c428066cbbd67ca6b17099cb1a9e', 'client_id': 'EXAMPLE', 'client_secret': 'codeGivenAfterTheFormWasFilled', 'grant_type': 'authorization_code', 'scope': '[SCOPES SEPARATED BY COMMA]'}`. 

5. If the correct credentials were given, the response should a JSON be like this: `{"access_token":"79d687a0ea4910c6662b2e38116528fdcd65f0d1","expires_in":3600,"token_type":"Bearer","scope":"[SCOPES]","refresh_token":"c1de730eef1b2072b48799000ec7cde4ea6d2af0"}`

6. The access_token is the one you will use to make requests to the Moodle API. The refresh_token is used to get a new access_token when the current one expires.

Note: If testing in Postman, you need to set encoding to `x-www-form-urlencoded` for POST requests.

## How to use the refresh token

When the access_token expires, your application must request a new one using the refresh_token.

1. Endpoint
Send a POST request to: `http://moodledomain.com/local/oauth2/refresh_token.php`  with the following
2. Request parameters: `{'client_id': 'EXAMPLE', 'client_secret': 'codeGivenAfterTheFormWasFilled', 'grant_type': 'refresh_token', 'refresh_token': 'c1de730eef1b2072b48799000ec7cde4ea6d2af0}`.
(See step 6 above for details on the refresh token.)

3. Response
If the request is successful, the response will contain a new access token and a new refresh token:
`{
    "access_token": "1703c39b0a9e462e2430a2e53da3299696bdefd5",
    "expires_in": 10800,
    "token_type": "Bearer",
    "scope": "[SCOPES SEPARATED BY COMMA]",
    "refresh_token": "c3150439e43649595a7b753ee1e99e041ee6aa0a"
}`.

4. Implementation Notes
   •	Use the new access_token for API requests.
   •	Replace the old refresh_token with the new one provided in the response.
   •	If the refresh_token itself expires, the user must authenticate again to obtain new credentials.

## Contributors
Apart from people in this repository, the plugin has been created based on the [local_oauth project] (https://github.com/projectestac/moodle-local_oauth) with the following contributors:

- [crazyserver] https://github.com/crazyserver
- [monicagrau] https://github.com/monicagrau
- [toniginard] https://github.com/toniginard
- [sarajona] https://github.com/sarjona
- [lfzawacki] https://github.com/lfzawacki
- [ignacioabejaro] https://github.com/ignacioabejaro
- [umerf52] https://github.com/umerf52

