## 模块升级程序
    
1. 获取最新代码：```git pull origin master```
2. 配置 .env 文件 *(若開發未提供配置則跳過)*
3. 安装套件：```composer install --no-interaction --prefer-dist --optimize-autoloader```
4. 刷新配置与释放缓存： ```php artisan dev```
5. 数据表结构更新： ```php artisan migrate```
6. 新增本次升级数据： ```php artisan db:seed --class=UpgradeSeeder```
