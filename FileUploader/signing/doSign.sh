#!/bin/sh

# To create a new, 100 self signed key:
# keytool -selfcert -alias sbcKey -genkey -validity 36500

rm -f FileUploader.jar
jar cvf FileUploader.jar -C ../bin .
jarsigner -storepass kathryn FileUploader.jar sbcKey
cp FileUploader.jar /var/www/sermons/mgmt
