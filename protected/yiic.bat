@echo off

rem -------------------------------------------------------------
rem  Yii command line script for Windows.
rem  This is the bootstrap script for running yiic on Windows.
rem -------------------------------------------------------------

@setlocal

set BIN_PATH=%~dp0

if "%PHP_COMMAND%" == "" set PHP_COMMAND=d:/webdev/php5.2/php.exe

%PHP_COMMAND% "%BIN_PATH%yiic.php" %*

@endlocal