所在公司的项目一共三个应用，其中的一个后台管理系统放在

```bash
/webservice/wwwroot/admin
```

Nginx 和 php-fpm 的运行身份是 www:www

```
# nginx 的配置文件路径
/usr/local/nginx/conf/nginx.conf

# php 的配置文件
/usr/local/php/etc/php.ini

# php-fpm 的配置文件
/usr/local/php/etc/php-fpm.conf
```

本来一切运营正常，但是在前几天的图片上过程遇到一个问题上传图片用的 OSS 服务是直接用阿里云的 ossfs 这个工具将其挂载到指定的目录下，于是将其挂载至 /mnt/ding-res 下

[OSSfs 配置过程](Aliyun_OSSfs_安装及配置.md)

上传的文件移动到该目录下的目录，这时候就遇到一个问题，
php 代码似乎无法访问根目录的上一级目录，原本以为是因为
上级目录没有 执行权限、所有者或所属组等问题

```bash
# 将所有配置文件、执行程序、以及目录分配给 www
chown -R www:www /xxx/xxx
```

最终发现仍然不能访问

问题分析

Nginx 是个网络代理将请求递交给 php 的 fast-cgi， 然而 php-cgi 是由 php-fpm [php fast-cgi 协议的实现] 这个守护启动的，那么问题可能出现 php-cgi 或者 php-fpm 

于是打开 Nginx 的相关配置文件，最终找到

```nginx
# /usr/local/nginx/conf/fastcgi.conf 下
# 限制了文件访问根目录的上级目录，注释掉就可以了
# 以“冒号”隔开多个路径

 fastcgi_param PHP_ADMIN_VALUE "open_basedir=$document_root/:/tmp/:/proc/";
```

重启 `php-fpm` 问题最终解决


----

参考文献： 

- [nginx+php 限制每个站点的目录范围，防止跨站](https://www.oschina.net/question/878142_106780)