user_servervars2
================


Utilisation des variables serveurs dans une optique fédération d'identités

Beaucoup d'idées sont reprises de [user_servervars de Jean-Jacques PUIG](http://apps.owncloud.com/content/show.php/user_servervars?content=158863)

Test unitaires (pour mémoire)

```
set PHPUNIT=c:\servers\xampp\php\phpunit
%PHPUNIT% --bootstrap tests\bootstrap.php apps\user_servervars2\tests
```

```
set XDEBUG_CONFIG=sublime.xdebug
```


Principes de développement:
- N'utiliser que les interface PUBLIQUES de owncore 7

server -> \OCP\IServerContainer



