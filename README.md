# Instagram collage builder service
Service for produce instagram collage from defined username, with possibilities to build collage from images defined by user.

#### Description
This service provide possibilities to login with your instagram account, then choose size of collage you wan to build, and choose the source of images, it is might be your feed or public feed, or your friend feed.
Additional feature it is, service provide you possibilities to define primary image color you want to prevail on collage, and service will try to find all images with that color, and put it on collage in order by color degradation.

#### How to install
``` 
git clone https://github.com/spalax/ua-webchalange-instagram-collage.git collage-builder
cd collage-builder
php -r "readfile('https://getcomposer.org/installer');" | php
php composer.phar install
```

#### How to develop/extend
Install first, then
```
./node_modules/bower/bin/bower install
./node_modules/grunt-cli/bin/grunt
```
When you change JS or CSS run **yes i did not configure watcher, but you may :=)**
```
./node_modules/grunt-cli/bin/grunt
```
