@echo off
if "%1"=="" goto help

set HTPASSWD=C:\Program Files (x86)\Apache Software Foundation\Apache2.2\bin\htpasswd.exe
rem set HTPASSWD=..\bin\htpasswd.exe
rem set HTPASSWD=htpasswd.exe

"%HTPASSWD%" -b users %1 %2
echo %1	%2>>users.txt

goto ende
:help

echo.
echo Syntax: newUser [Name] [Password]
echo.

cmd /k

:ende