Tic Tac Toe Install Guide
=========================

Install Composer
----------------

If you already have composer installed - skip this step

To download composer in environment directory via curl use:

```
curl -sS https://getcomposer.org/installer | php
```

Or if you don't have curl:

```
php -r "readfile('https://getcomposer.org/installer');" | php
```

Install application
-------------------

Use composer to install application vendors and generate autoload file:

```
composer.phar install
```

You must have Apache installed to make application works properly by default, or you can start PHP's own server to test it.
Make sure that environment root targeted to the "web" directory.

Additionl info:
---------------

```
http://silex.sensiolabs.org/download
```

```
https://getcomposer.org/download/
```