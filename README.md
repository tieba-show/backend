# backend
贴吧头像墙-后端项目

# 部署方式
- 配置好MongoDB以及Redis环境
- 安装好PHP 5.5或更高版本，以及对应版本下的MongoDB和Redis扩展，GD库
- 配置好config.php下的各项配置文件
- CLI下执行php task.php，后端即启动完毕。

# 前后端混合部署
改项目前端使用Vue.js独立开发，前后端完全分离。但是如果要混合部署，请将前端相关项目放在主目录（即WEB根目录下，比如说/var/www/html/），后端所有文件放在某一个子目录中，然后修改前端配置文件config.js中对应的后端API地址，并且正确修改config.php中用于存储爬虫抓取图片和输出图片的路径。
