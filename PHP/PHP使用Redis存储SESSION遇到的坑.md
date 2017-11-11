## 背景概述

> 改造公司的基于 TP3.2 做的项目，构想基于 Redis 做分布式 Session 分享

- thinkphp 3.2
 
## 配置过程

```
# 将下载的 Redis.class.php 放置到 /ThinkPHP/Library/Think/Session/Driver 下

# db.php 中 Redis Session配置
'SESSION_AUTO_START' => true,           // 是否自动开启Session
'SESSION_TYPE'       => 'redis',        //session类型
'SESSION_DB_INDEX'   => 2,              //数据库[自定义属性]
'SESSION_PERSISTENT' => 0,              //是否长连接(对于php来说0和1都一样)
'SESSION_CACHE_TIME' => 10,             //连接超时时间(秒)
'SESSION_EXPIRE'     => 1440,           //session有效期(单位:秒) 0表示永久缓存
'SESSION_PREFIX'     => '',             //session前缀、
'SESSION_REDIS_HOST' => '192.168.80.80', //分布式Redis,默认第一个为主服务器
'SESSION_REDIS_PORT' => '6379',          //端口,如果相同只填一个,用英文逗号分隔
'SESSION_REDIS_AUTH' => '',              //Redis auth认证(密钥中不能有逗号),如果相同只填一个,用英文逗号分隔

# 关键问题，使用 session_set_save_handler 后无法 write session 值到 redis 中

session_set_save_handler(
    array(&$hander,"open"), 
    array(&$hander,"close"), 
    array(&$hander,"read"), 
    array(&$hander,"write"), 
    array(&$hander,"destroy"), 
    array(&$hander,"gc")
); 

# 最终排除得到的结论是 如果 Redis.class.php 绑定的 read（） 返回的是非 String 值时不能 write session 值，处理一下即可
```

