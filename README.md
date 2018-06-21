# Typecho 批量更改文章分类插件 PostsCategoryChange

## 插件简介

> 1. 批量更新文章分类

## 注意（灰常重要）

> 1.  由于Typecho 文章管理页面(“admin/manage-posts.php”)，没有提供插件点，启用之前请自己在“admin/manage-posts.php”文件里添加一个插件钩子。
![添加插件钩子](https://huangweitong.com/usr/uploads/2018/06/1366006123.png)
```php
Typecho_Plugin::factory('admin/manage-posts.php')->bottom();
```
> 2. 在批量更新文章分类的时候，请先确认**被操作的文章是否都只能有一个分类。**
> 3. 如果有个别文章存在多个分类的请手工修改分类，不要使用本插件。

## 安装方法

> 1. 至[releases](https://github.com/fuzqing/PostsCategoryChange/releases)中下载最新版本插件；
> 2. 将下载的压缩包进行解压，文件夹重命名为`PostsCategoryChange`，上传至`Typecho`插件目录中；
> 3. 后台激活插件。

## 使用方法

> 1. 到文章管理界面选择你要修改分类的文章 -> 选中项 -> 移动 -> 选择一个分类。
> 2. 看图 ![makeChange](http://p7dh1laws.bkt.clouddn.com/makeChange.gif)

## 更新日志

### 2018.6.20

> 1. 发布Typecho 批量更改文章分类插件 PostsCategoryChange 第一个版本；
