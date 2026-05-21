@echo off
echo Adding local test domains to hosts file...
echo.
echo 127.0.0.1 esthers.local >> C:\Windows\System32\drivers\etc\hosts
echo 127.0.0.1 salengafarm.local >> C:\Windows\System32\drivers\etc\hosts
echo.
echo Done! Domains added:
echo - esthers.local (Esther's Flower Garden)
echo - salengafarm.local (Salenga Farm)
echo.
echo Test URLs:
echo - http://salengafarm.local:8000
echo - http://esthers.local:8000
echo.
pause
