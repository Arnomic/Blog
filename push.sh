#!/bin/bash
# 自动提交脚本
read -p "输入备注：" message
echo ===== 添加到仓库 =====
git add .
echo ===== 提交到仓库 =====
git commit -m $message
echo ===== 推送到远程 =====
git push origin master

