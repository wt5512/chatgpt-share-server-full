
# ChatGPT Share Server

## 安装步骤

### 一、初始化数据库
...

### 二、修改docker-compose.yml配置
1. 修改接入网关参数 `CHATPROXY`
2. 修改`TTID`参数，请联系客服V：wtony123获取

### 三、修改dujiaoka/env配置
修改`APP_URL`为具体域名，必须是`buy.xxx.com`。例如：
```
APP_URL=https://buy.xxx.com
```

### 四、修改nginx配置
1. 准备证书  
   证书目录`nginx/cert`生成3个域名证书，每个证书两个文件，放在此目录。  
   命名规范为：
   ```
   www.xxx.com.crt
   www.xxx.com.key

   chat.xxx.com.crt
   chat.xxx.com.key

   buy.xxx.com.crt
   buy.xxx.com.key
   ```
2. 修改`nginx/conf/nginx.conf`文件中的`xxx.com`为具体的顶级域名

### 五、启动程序
```sh
cd chatgpt-share-server
docker compose pull
docker compose up -d --remove-orphans
```
