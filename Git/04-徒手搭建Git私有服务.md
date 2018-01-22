## 前言
访问Git资源的 URI 一般有 https 和 ssh 两种姿势最常用的是ssh, 比如:
```
git@xxx.com:xxx/xx.git
```
对于ssh早期的认知一直停留在终端登录,比如我们在终端远程服务器的时候通常使用ssh形式登录, 比如:
```
ssh root@xxx.com
```
ssh可以配置免密登录, 所以git一般配置好公私钥后即跳过密码验证过程.
git本身就提供service与client打包在一起的服务, 自然不用额外安装什么
毫不费力的使用ssh和git直接配置一个git仓库.过程如下:

## 环境
- centos 7.2
- git
- sshd
- gitweb

## 过程

### 1. 建立git用户
```
# -m 同时在 home 目录下建立文件夹
adduser -m git 
# 编辑 /etc/passwd文件中git登录时启动文件
# 将 git:x:1001:1001:,,,:/home/git:/bin/bash
# 修改为 git:x:1001:1001:,,,:/home/git:/usr/bin/git-shell
# git-shell 可以阻止git用登录, 一旦登录就退出
```

### 2. 配置SSH免密登录
```
####### 服务端配置 #######

# 想少出现问题请直接在服务端用 ssh-keygen 生成号秘钥直接cp到客户端
ssh-keygen -t rsa -C xxx@xx.com

# 复制公钥到authorized_keys
cat ~/.ssh/id_rsa.pub > authorized_keys

# 复制 id_rsa 私钥到客户端 >>> 自己搞定

# 编辑 sshd_config
vim /etc/ssh/sshd_config

# 打开或添加下面这些配置
RSAAuthentication yes
PubkeyAuthentication yes
# 这里的路径是相对登录用户的home目录下的
# 比如git则是 /home/git/.ssh/authorized_keys
AuthorizedKeysFile      .ssh/authorized_keys

####### 客户端 ########
# 将服务端的 id_rsa 秘钥复制到本地客户端 /home/.ssh/vagrant下, 根据实际情况路径自己设置
# 编辑当前用户home/.ssh/config文件, 没有的话新建一个
vim home/.ssh/config
# 添加内容如下, 自己修改一下
Host vagrant
    HostName 192.168.80.80
    Port 22
    User xxx@qq.com
    PreferredAuthentications publickey
    IdentityFile /Users/ddd/.ssh/vagrant/id_rsa
# 以上每添加一个Host就是一个秘钥配置, 你可以针对性对不同domain配置不同秘钥
# 或者同一个domain配置不同Host从而分别用不同服务, Host可以理解为HostName(也就是IP)的别名
# 添加完后就可以使用 ssh -T git@vagrant 这种形式替代 git@192.168.80.80 姿势来登录
# 如果出现错误的, 根据提示自行百度一下
```

## 3. 配置git服务端
```
###### 服务器端 ######
# 添加git服务, 其实很简单, /home/git/下随便找个文件夹 比如 
cd /home/git/
# sample.git 文件夹自己建一个, 仓库一般用.git做后缀
mkdir repositories/sample.git
# 这样后面就可以用 git@vagrant:repositories/sample.git 访问资源了
# 创建仓库, 参数 --bare 是创建一个裸库, 不可以使用客户端的一些命令, 比如 add commit
git --bare init
# 后面如果 ssh -T git@vagrant 成功了访问就没有问题
# 记得如果权限不够暂时将 repositories 的权限提升为 777 先走通流程后面再说

###### 客户端设置 ######
# 建立仓库, 随便找个文件夹
git init
# 添加远程仓库
git remote add origin git@vagrant:repositries/sample.git
# 添加文件, 提交, 推送
echo "Hello World!" > README.md
git add .
git commit -m "init"
git push origin master
# 至此全部过程全部结束
```

