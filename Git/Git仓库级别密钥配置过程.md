## 背景及前置知识
- 系统背景
```bash
CentOS 7.2
Gitlab
```
- git config 配置的 3 个级别
```bash
# 知识点
    --system # 系统级别，位于 /etc/gitconfig 下对影响该系统下所有用户
    --global # 用户级别，位于 ~/.gitconfig 下，仅影响该用户
    --local  # 仓库级别，位于仓库的 .git/config ， 仅影响该仓库

# 用法 ： 
    git config [--system|global|local] user.name "xxx" # 默认就是 --local 可不填，
```

## 配置过程

### 1. 生成自定义密钥对

```bash
# 生成的秘钥需要另外制定路径，避免误操作覆盖默认文件
/home/xxx/.ssh/*        # 默认 id_rsa
/home/xxx/.ssh/dinglc/* # 自定义

# 如果没有 ssh-keygen 需要安装 
yum install openssl

# 生成自定义 key
ssh-keygen -t rsa -C "xxx@xx.com" -b 4096 

# 接下来制定文件路径和名称为 /home/xxx/.ssh/dinglc/id_rsa 即可
```
### 2. 在 .ssh 目录下添加 config 配置文件
```bash
# 添加秘钥配置文件 [路径：/home/xxx/.ssh/config]
Host xxx-gitlab                           # 别名，以后用来替代域名部分
    HostName gitlab.com                      # 域名或IP
    Port xxxx                                # 默认22可去掉此项
    User xxx@xxx.com                         # 注意是邮箱
    PreferredAuthentications publickey
    IdentityFile /home/xxx/.ssh/id_rsa_xxx   # 生成的单独项目私钥
    
```

### 3. 添加密钥至ssh中

```bash
# 添加到 ssh 秘钥配置中 
ssh-add /home/xxx/.ssh/id_rsa_xxx

# 如果出现错误提示 Could not open a connection to your authentication agent.
ssh-agent bash

# 将生成的 id_rsa.pub 密钥添加到 gitlab SSH KEY 配置表中
```

### 4. 测试配置文件是否可用

```bash
# 测试一下配置是否成功
ssh -T git@xxx-gitlab # @后面时 Host 别名，别搞错

# 需要添加 publickey SSH key 至 gitlab 中，否则认证失败
Permission denied (publickey).

# 如果出现错误，输入yes
The authenticity of host xxx.xxx.xxx.xxx (xxx.xxx.xxx.xxx) can not be established.
ECDSA key fingerprint is SHA256:xxxx.
Are you sure you want to continue connecting (yes/no)? yes

# 成功后出现  Welcome to GitLab, xxx!
```

### 5. 设置仓库配置参数
```bash
# 建立一个新的仓库目录并进入
mkdir testgit
cd testgit

# 初始化仓库
git init 

# 设置单独仓库的用户名和邮箱
git config user.name "xxx"
git config user.email "xxx@xx.com"

# 设置完成后可以在该仓库的 .git/config 文件找到
[core]
	repositoryformatversion = 0
	filemode = true
	bare = false
	logallrefupdates = true
[remote "origin"]
        url = git@github.com:xxx/xxx.git
        fetch = +refs/heads/*:refs/remotes/origin/*
[user]
	name = someone
	email = xxx@xx.com

# 或者用 git config -l 查看
core.repositoryformatversion=0
core.filemode=true
core.bare=false
core.logallrefupdates=true
user.name=someone
user.email=xxx@xx.com

```

### 6. 操作远程仓库 [ 关联已存在的仓库 ]

```bash
# 关联远程仓库 [本地空仓库]
# git init
# git remote add origin git@github.com:xxx/xxx.git
git remote add origin git@dinglc-gitlab:xxx-xx/xxx-xx-xx.git
# 检出远程 master 到本地
git checkout -b master origin/master

# 跟踪远程分支
git branch --set-upstream-to=origin/master

```
