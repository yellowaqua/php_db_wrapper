php_db_wrapper
==============

This repository is database wrapper for php.
* there is a interface.
* there is a class for ODBC.(PHP5.5 and PHP5.6)
* there is a class for PDO.(PHP5.4)

The ODBC Wrapper class can be used on the following systems:
* PHP5.5/PHP5.6 on Windows Server2012 (installed SQLServer Native Client 11.0)
* SQL Server 2012


The PDO Wrapper class can be used on the following systems:
* PHP5.4 on Windows Server2012
* SQL Server 2012
(must use Microsoft Drivers 3.0 for SQL Server for PHP)

php.ini edit
> extension=php_pdo_sqlsrv_54_nts.dll
