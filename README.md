authMethods
===========

Client < > Server authentication and authentication transfer methods in the web context.

![Screenshot](http://grabs.lucasmouilleron.com/Screen%20Shot%202015-11-23%20at%2015.32.06.png)

Authentication
--------------
`Authentication` is the process of __confirming identity__.

This process is used in client / server apps to __grant access to resources__.

Once a resource owner is authenticated, this state of authentication needs to be __persisted or transfered__.
Here are some possible methods to do so in the case of stateless systems, such as _REST APIs_ and _apps over HTTP in general_.

Security preambule
------------------

### Hash function
A `hash function` is any function that can be used to map digital data of arbitrary size to digital data of fixed size, with slight differences in input data producing very big differences in output data. 
The output of a hash function is called the `hash`.

### Data integrity
A simple way to ensure a piece of data was __not modified when transfered__ from a sender to reciever is to __hash__ it.

This will produce the `signature` of the data.

The __sender signs the data__ and sends the original data __and__ the signature.
The __reciever resigns the data__ and if the signature is the same, the data was __not modified__.

This method _does not guaranty the aunthenticity of the data_, as it can be forged and signed by anyone.

### Digital signing
A `digital signature` is a mathematical scheme for demonstrating the authenticity and integrity of a digital message or document.
Digital signing involves a `shared secret key`, __known only to the sender and reciever__.

A simple way of signing data is to __append the shared secret key to it and hash this concatenated data__.

This will produce the `secret signature` of the data.

The __sender secret signs the data__ and sends the original data __and__ the signature.
The __reciever re secret signs the data__ (_using the shared secret key_), and if the signature is the same, the data was __not modified__ since it has been signed by the sender and was __issued by someone who knows the__ `shared secret key`.

This simple method _does not encrypt the data_.

### HTTPS
`HTTPS` is `HTTP` over `TLS`.
It means requests performed with __HTTPS can't be wiretapped__.

The content of the __request (header + query parameters)__ and the __response (headers + body)__ are __encrypted and not sniffable__.

One caveat is that HTTPS requests query parameters can be _loged on web servers_.
If logins and passwords are passed via _GET query parameters_, they can be found on web servers log files.
Using _request headers or POST parameters_ is therefore recommanded, as they are not logged by servers.

HTTP basic authentication
-------------------------
`Basic authentication` is the __simpliest form of stateless authentication method__.
The user credentials (login and password for example) are sent to the server within the __request headers__.

This method over HTTP is obviously unsafe as the credentials could be obtained by sniffing the HTTP requests.
This method is __safe only when used over HTTPS__.

### Flow 

    +--------------------+                                       +--------------------+
    | CLIENT             |                                       | SERVER             |
    |                    |                                       |                    |
    | 1. Sends the       | GET /user                             |                    |
    | credentials with   | Authorization: Basic czZCaGRSa        |                    |
    | every request      | ------------------------------------> | 2. Validates the   |
    |                    | HTTP 200 OK                           | credentials and    |
    |                    | {name:"lucas"}                        | process request    |
    |                    | <------------------------------------ |                    |
    +--------------------+                                       +--------------------+

1. The user __sends__ its `credentials` with _every request_, within the _request headers_ (the credential string is base64(login:password))
2. The server __validates__ the user `credentials`, __processes__ the request and __sends back__ the `data`.

### Pros
- Very simple
- Secure over HTTPS

### Cons
- Requires HTTPS
- Does not allow authentication delegation (such as OAuth)
- Does not allow expiry
- The crednetials must be sent with every authenticated call to the resource
- The credentials validation is performed on every call

### Resources
- [HTTP basic authentication wikipedia page](http://www.wikiwand.com/en/Basic_access_authentication)

Cookie and server persisted sessions
------------------------------------
`Cookie and server persisted sessions` is the most widely used authentication persistence method.
This method involves a `client side cookie` and a `server side stored session`.

A `server side stored session` is an object containing relevant user data. It is stored on the server.
When the user calls server methods, the server retrieves the user's object from its store (and may use it as a context).

Implementation examples : PHP sessions, ASP sessions or a CMS custom sesssions system.

### PHP sessions flow

    +--------------------+                                       +--------------------+
    | CLIENT             |                                       | SERVER             |
    |                    |                                       |                    |
    | 1. Logs in         | POST /login                           |                    |
    |                    | username=...&password=...             |                    |
    |                    | ------------------------------------> | 2. Validates the   |
    |                    | HTTP 200 OK                           | user against db    |
    |                    | Set Cookie PHPSESSIONID               | and create the     |
    | 3. Stores the      | <------------------------------------ | session            |
    | cookie             |                                       |                    |
    |                    |                                       |                    |
    | 4. Sends the       | GET /user                             |                    |
    | cookie with every  | Cookie PHPSESSIONID                   |                    |
    | request            | ------------------------------------> | 5. Finds the       |
    |                    | HTTP 200 OK                           | session in the     |
    |                    | {name:"lucas"}                        | sesssion store and |
    |                    | <------------------------------------ | process request    |
    +--------------------+                                       +--------------------+

1. The user __logs in__, for example by providing his `credentials`
2. The server __validates__ the user `credentials` and __fetches__ some relevant `data` (such as the _login, the cart, etc._) and __stores__ them to a `session object` (persisted as a file on the server)
3. A `cookie` is __installed on the client__ referencing the server side `object id
4. For every next call, the `session cookie` is sent
5. The server __reads__ the `object id` from the `cookie`, __retrieves__ relevent `data` from the session file, __processes__ the request (using the session data as a context, in this case the login) and __sends back__ the `data`

### Pros
- Quite easy to implement
- The browser automatically handles sending the cookie to the server on every request
- Battle tested

### Cons
- Not easy to use accross multiple server side languages (eg a nodejs and a php server)
- Not stateless. The sessions are stored on the server. Not ideal when scaling up.
- Cookies don't do well across different domains

### Resources
- [PHP sessions documentation](http://php.net/manual/en/features.sessions.php)

Stateless tokens
----------------
A `stateless token` is a self defined amount of secret signed data.

Generaly, it is composed of a `header`, a `payload` (the data) and the `signature`.
As the `payload` __is not encrypted__, it should _not contain senstive informations_, such as the password of the user. It should contain its login though.

In a _client / server context_, the `token` _issuer_ is the server.
The client _carries_ the `token` and _sends_ it with every server request, as a __mean of authentication__.

Because the `token` is __secret signed by the server__, the token can _not be forged_ by a third party.
Because the `token` __contains the payload__, the server does _not need to store any data_ on its side.

For the token not to be sniffed and reused as is, the use of HTTPS is recommanded.

An example of implementation is `Json Web Token (JWT)`. They are url-safe (which means the header and payload are base64 encoded) and are therefore very convenient for client / server web apps.

### JWT flow 

    +--------------------+                                       +--------------------+
    | CLIENT             |                                       | SERVER             |
    |                    |                                       |                    |
    | 1. Logs in         | POST /login                           |                    |
    |                    | username=...&password=...             |                    |
    |                    | ------------------------------------> | 2. Validates the   |
    |                    | HTTP 200 OK                           | user against db    |
    |                    | {token:"..."}                         | and create the     |
    | 3. Stores the      | <------------------------------------ | token              |
    | token in a cookie  |                                       |                    |
    |                    |                                       |                    |
    | 4. Sends the       | GET /user                             |                    |
    | token with every   | token="..."                           |                    |
    | request            | ------------------------------------> | 5. Validates the   |
    |                    | HTTP 200 OK                           | token and          |
    |                    | {name:"lucas"}                        | processes request  |
    |                    | <------------------------------------ |                    |
    +--------------------+                                       +--------------------+

1. The user __logs in__, for example by providing his `credentials`
2. The server __validates__ the user `credentials` and __fetches__ some relevant data (such as the _login, etc._) and __add__ them into a `token`. The `token` is sent back to the user.
3. The `token` is __stored in cookie on the client__ for further usage
4. For every next call, the `token` is sent. The token can be sent as a `GET parameter` or within the `request header`.
5. The server __processes__ the request :
    - The server __extracts__ the `header` and `payload` from the `token` and _resign_ then with the `secret key`
    - If the `signature` is the same, the `token` is _valid_
    - The server may as well check the token __is not expired__. For this purpose, the expiry date can be stored in the payload.
    - The server then __processes__ the request (using the token data as a context, in this case the login) and __sends back__ the `data`

This _JWT flow_ is actually an _OAuth2 staeless implicit flow_ (see below).

### SSO usage
JWT `token` can be used for __SSO puropose__.

It involves an __authentication server__ and other __services in need of authenticated users__.

- Ideally, the authentication server __register__ services : configuration of the service `redirect page` after login, and the `private shared signing key`
- Every service must know the authentication server `private shared signing key`
- When the user __accesses a service__ :
    - If a JWT `token` is provided (cookie, get param), the service validates the `token` :
        - If the `token` is _valid_, using the `payload` (email, name, etc.)
        - If not, redirect to the `authentication server login page`
    - If no JWT `token` is provided, redirect to the `authentication server login page`
- When the user __logs in__ on the `authentication server login page` : 
    - The authentication server __validates the user and produces__ a JWT `token`
    - The user is then redirected to the `redirect page` of the service with the JWT `token` (get param)

### Pros
- Stateless and performance, as the token is self defined and does not need to be stored server side
- Works easily with multiple language servers (for example PHP <-> nodejs)
- No cross site requests flaws

### Cons
- The token must be added as a parameter with every authenticated call to the resource
- Does not support revoking (because it is stateless)
- Requires HTTPS

### Resrouces
- [JWT portal](http://jwt.io)
- [JWT tokens for Socket.io](https://auth0.com/blog/2014/01/15/auth-with-socket-io)

OAuth
-----

Oauth permits to __grant__ a `consumer` (an app, for example Coca Cola) authorization on behalf of a `resource owner` (a user, for example you) to __perform actions__ on a third party `resource server` (a 3rd party service, for example Facebook).

To do so, the `consumer` __creates an app__ within the `3rd party service` platform. The `consummer` app comes with a `consumer ID` and a `consumer secret`.

The `consumer secret` is never exposed to the `resource owner` and is used only to __authentificate__ the client when requesting the `user access token`.

The `consumer` then __retrieves__ the `user access token` on behalf of the `resource owner` and sends it along with every call to the `resource server`.

This process gives better _flexibility_ for the authentication process and allows __delegation__ :

- At no point the user `credentials` are known to the `consumer`
- A `user access token`can be revoked by the `resource owner`

### OAuth 2.0 code flow

    +--------------------+                                       +--------------------+
    | RESOURCE OWNER     |                                       | SERVER             |
    | via its browser    |                                       | 3rd party service  |
    |                    |                                       |                    |
    | 2. Logs  in and    | ------------------------------------> | 3. Validates the   |
    | grant access to    |                                       | user against db    |
    | the consumer       |                                       | and produce code   |
    |                    |                                       |                    |
    |                    |                                       |                    |
    |               +------------------------------------------- |                    |
    |               |    |                                       |                    |
    |               |    |                                       |                    |
    |               |    |                                       |                    |
    |               |    |                                       |                    |
    |               |    |                                       |                    |
    +---------------|----+                                       |                    |
                    |                                            |                    |
        ^           | 4. Passes the code to the consumer on the  |                    |
        |           | redirect URI                               |                    |
        |           |                                            |                    |
        | 1. Redirects to 3rd party login page                   |                    |
        | GET /oauth2/authorize                                  |                    |
        | consumerID=...&redirectURI=...                         |                    |
        |           |                                            |                    |
        |           v                                            |                    |
                                                                 |                    |
    +--------------------+                                       |                    |
    | CONSUMER           |                                       |                    |
    | Client app         |                                       |                    |
    |                    |                                       |                    |
    | 5. Requests access | POST /oauth2/token                    |                    |
    | token              | code=...&consumerID=...               |                    |
    |                    | &consumerSecret=...                   |                    |
    |                    | ------------------------------------> | 6. Validates the   |
    |                    | HTTP 200 OK                           | consumer against   |
    |                    | {token:"...",refreshToken="..."}      | db and create the  |
    | 7. Stores the      | <------------------------------------ | token              |
    | token in a cookie  |                                       |                    |
    |                    |                                       |                    |
    | 8. Sends the       | GET /user                             |                    |
    | token with every   | token="..."                           |                    |
    | request            | ------------------------------------> | 9. Validates the   |
    |                    | HTTP 200 OK                           | token and          |
    |                    | {name:"lucas"}                        | processes request  |
    |                    | <------------------------------------ |                    |
    +--------------------+                                       +--------------------+

1. The `consumer` __redirects or sends__ the `resource owner` to the `3rd party service` login page. The `consumer id` and the `redirect URI` are passed along.
2. The `resource owner` __logs in__ and __grants access__ to the `consumer` (permissions may be presented for review) 
3. The `3rd party server` __validates__ the user `credentials` and __produces__ a `code`
4. The `3rd party server` __redirects__ the `resource owner` to the `redirect URI` with the generated `code`
5. The consumer __requests__ a `user access token` to the `3rd party server` with the `code` recieved, its `consumer ID` and its `consumer secret`
6. The `3rd party server` __authenticates__ the `consumer` and __issues__ the `user access token` and the `refresh token`
7. The `token` is __stored in cookie on the client__ for further usage
8. For every next call, the `token` is sent. The token can be sent as a `GET parameter` or within the `request header`.
9. The server __processes__ the request by `validating` the `token` and __sends back__ the `data`

The `consumer` can __refresh__ the `user access token` if it _expired or is about to expire.
The `consumer` calls `3rd party server` refresh token endpoint with its `consumer id`, `consumer secret` and the `refresh token` retrieved on step `6`. 

### Other OAuth 2.0 flows

#### The pin flow

The _pin flow_ is very similar to the _code flow_. The difference is the step `4` is __not automatic__.
This _flow_ is typically used if the `consumer app` is __not web capable__ and therefore can not perform the step `4`. 
The `code`, called the `pin`, is given to the `resource owner` so he can __input it manually__ in the `consumer app`.
The `pin` is smaller than the `code` so it easier for the `resource owner` to __manipulate it__.

#### The implicit flow

The _implicit flow_ is a simplified _code flow_ optimized for clients implemented in a browser using a scripting language such as JavaScript.
In the _implicit flow_, instead of issuing a `code`, the` 3rd party service` returns the `user access token` directly.
In this _flow_, the `consumer` is __not authentificated__ by the `3rd party server`.
    
### OAuth 1.0
OAuth 1.0 is the ancestor of OAuth 2.0.

Main differences are the `user access token` never expire, the `consumer app` needs to sign some requests and more roundtrips are involved.

It is still used by Twitter.

### Pros
- The `consumer` can't access the `resource owner credentials`
- Revoking `tokens`

### Cons
- Not stateless
- OAuth 1.0 is a bit painful
- Requires HTTPS

### Resources
- [Salesforce article about OAuth 2.0](https://www.salesforce.com/us/developer/docs/api_rest/Content/intro_understanding_web_server_oauth_flow.htm)
- [Facebook article about implenting OAuth 2.0](https://developers.facebook.com/docs/facebook-login/manually-build-a-login-flow/v2.1)
- [Implementing OAuth 2.0 as a server](https://github.com/kivra/oauth2)
- [OAuth 2.0 RFC](https://tools.ietf.org/html/rfc6749#section-4)
- [Twitter article about implementing OAuth 1.0](https://dev.twitter.com/web/sign-in/implementing)
- [OAuth 1.0 RFC](http://oauth.net/core/1.0a/#anchor9)

OAuth stateless
---------------

This is a stateless implementation of OAuth 2.0.
Like OAuth 2.0, the `consumer` __does not know__ the `resource owner` `credentials`.
Because it is a _stateless flow_, no `tokens` or `codes` are persisted, which is great for performance and scaling up.
But because it is a _stateless flow_, it is not possible to revoke a `user access token`.

Implementation : 

- The `code` and `user access tokens` are `JWT tokens` so the flow is stateless
- The `code` provided in step `4` is `data` (user id, client id and expiry) __signed__ with a `secret` __know only__ to the `3rd party service` (and not the `consumer`)
- When the `consumer` __requests__ the `user access token`, the `3rd party server` __verifies__ the `code` and makes sure the `consumer ID` in the `code` is __the same__ as the one provided by the `consumer`. Like in OAuth 2.0, it still __authenticates__ the `consumer`.
- The `user access token` issued by the `3rd party server` is a `JWT token` containing relevant `data` (login, etc.)
- When the `consumer` __performs calls__, the `token` is __verified__ (signature and expiry) by the `3rd party service`
- Because `codes` and user `access tokens` are __signed by the 3rd party service__, they can't be forged by `consumers` or `owners`
