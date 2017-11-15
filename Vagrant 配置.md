## Vagrant 简介

Vagrant 是一款用来构建虚拟开发环境的工具，非常适合 php/python/ruby/java 这类语言开发 web 应用，“代码在我机子上运行没有问题”这种说辞将成为历史。

我们可以通过 Vagrant 封装一个 Linux 的开发环境，分发给团队成员。成员可以在自己喜欢的桌面系统（Mac/Windows/Linux）上开发程序，代码却能统一在封装好的环境里运行，非常霸气。

## 环境
Mac OSX 10.13

## 准备
|软件|版本|下载地址|
|-|-|-|
|Vagrant|1.9.3|[官网](https://www.vagrantup.com/downloads.html)|
|VirtualBox| 5.1.18 r114002|[官网](https://www.virtualbox.org/wiki/Downloads)|
|precise64.box|-|[下载地址](http://files.vagrantup.com/precise64.box)|
|各种box||[Official Site](http://www.vagrantbox.es/)|

## 过程
1. 首先安装 vagrant 和 virtualBox, 常规步骤安装即可。

2. 执行以下命令

```bash
######################### 安装 ########################

mkdir vagrant/linux-dev
cd vagrant/linux-dev
# 将镜像添加到 vagrant 列表中
vagrant box add {title} {box_path}

# 实例化一个镜像
vagrant init {title}

# 修改 Vagrantfile 为 public 网络 【简单理解为在路由中可分配到何物理机同级的ip】
config.vm.network "public_network", ip: "192.168.56.80"

# 私有网络，在物理机内组成内网，虚拟机可以访问公网和局域网，但局域网和公网无法直接访问虚拟机网
# config.vm.network "private_network", ip: "192.168.56.80"

# 修改 Vagrantfile 为文件映射
config.vm.synced_folder "/Users/xxx/vagrant", "/vagrant_data"

# 启动
vagrant up

# 进入SSH 
vagrant ssh

# 注意：
# 可以使用 shell 工具连接
# 主机/端口 : localhost:2222
# 账户/密码 : vagrant/vagrant

```
# 常用命令

|命令|作用|
|-|-|
|vagrant box add|添加box的操作|
|vagrant init|初始化box的操作，会生成vagrant的配置文件Vagrantfile|
|vagrant up|启动本地环境|
|vagrant ssh|通过 ssh 登录本地环境所在虚拟机|
|vagrant halt|关闭本地环境|
|vagrant suspend|暂停本地环境|
|vagrant resume|恢复本地环境|
|vagrant reload|修改了 Vagrantfile 后，使之生效（相当于先 halt，再 up）|
|vagrant destroy|彻底移除本地环境|
|vagrant box list|显示当前已经添加的box列表|
|vagrant box remove|删除相应的box|
|vagrant package|打包命令，可以把当前的运行的虚拟机环境进行打包|
|vagrant plugin|用于安装卸载插件|
|vagrant status|获取当前虚拟机的状态|
|vagrant global-status|显示当前用户Vagrant的所有环境状态|

## 参考
[使用 Vagrant 打造跨平台开发环境](https://segmentfault.com/a/1190000000264347)