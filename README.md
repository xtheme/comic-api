## 模块升级程序
    
1. 获取最新代码：```git pull origin master```
2. 配置ENV *(若開發未提供配置則跳過)*
3. 安装套件：```composer install --no-interaction --prefer-dist --optimize-autoloader```
6. 发布视图资源： ```npm run prod```
7. 刷新配置与释放缓存： ```php artisan release```
8. 队列： ```php artisan queue:work --timeout=300 &```

