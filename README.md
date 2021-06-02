# RUNNING THE CODE

From a terminal at a *nix system.

```shell
username@host:~$ php index.php
```

If you don't have php in you PATH for execution you can execute it like this:

```shell
username@host:~$ `which php` index.php
```

This results in a output.xml file a the same level as index.php

## ASSUMPTION

Internet Connection:
Internet connection to fetch XML-content is assumed, If not, 
set "loadExternalSource" to false in configuration.ini in root-folder

While testing for last time before release, the http://musicmoz.org was not reachable, I throw an 
exception if file not reachable, so at the moment "loadExternalSource" is set to false, if want to try with
internet file set "loadExternalSource" to true in configuration.ini in root-folder.

Release dates:
The release dates in XML-content is in various formats fx. "2000.07.29", "1998", "1993/1994".
The date-format filtered assumed to be yyyy.mm.dd, so some release might be missing in XML-output file.