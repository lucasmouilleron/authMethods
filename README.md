authMethods
===========

Server persisted sessions
-------------------------

Server persisted sessions are objects containing relevant users data stored on the server.
When the user calls server methods, the server retrieves the user's object from its store (and may use it as a context).

Implementation examples : PHP sessions, ASP sessions or a CMS custom sesssions system.

PHP sessions flow : 

- PHP sessions are composed from two parts. An object on the server and a cookie on the client's browser
- The user logs in, for example by providing his login and password to a `/login` API route
- The server _validates_ the user and fetch some relevant data (such as the _login, the cart, etc._) and stores them to an object (persisted as a file on the server)
- A cookie is installed on the client side referencing the server side object id.
- For every next call,  the session cookie is sent. The server reads the object id from the cookie and retrieves relevent data from the session file and processes the user's request

[Official doc](http://php.net/manual/en/features.sessions.php)

### Pros
- No need to alter the frontend links or authenticated server calls, as the browser automatically handles sending the cookie to the server on every request
- Battle tested

### Cons
- Cookies don't do well across different domains

Stateless tokens
----------------

A stateless token is a self defined amount of signed data.

Example of implementation : Json Web Token (JWT) is a self contained url-safe token.

JWT flow: 

- The user logs in, for example by providing his login and password to a `/login` API route
- The server _validates_ the user and fetch some relevant data (such as the _login, the cart, etc._) and generates the token
- The token is _encoded and signed with a private key_ known only to the server
- The token is given to the user as a response to the log in request
- The token is then kepts on the client side and used for every next authenticated call
- For every next call, the server then _validates_ the token (_expiry, consistency_) and _decodes_ it to retrieve the relevant data and processes the user's request

[JWT portal](http://jwt.io)

### Pros
- Stateless and performance, as the token is self defined and does not need storing server side
- Works cross platforms, 
- No cross site requests flaws

### Cons
- The token must be added as a parameter to every authenticated call to the server

Cross servers auth
------------------
- For instance : PHP <-> nodejs
- JWT is ideal
- [An example form Auth0](https://auth0.com/blog/2014/01/15/auth-with-socket-io)

