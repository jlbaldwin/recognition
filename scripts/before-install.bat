set FOLDER=%HOMEDRIVE%\projects\recogaws\recognition\recogportal

if exist %FOLDER% (
  rd /S /Q "%FOLDER%"
)

mkdir %FOLDER%