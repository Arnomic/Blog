> 我的分支命名规则是 feature/username/2171121/any_task_name, 日子久了就会在本地产生很多无用的分支，想一次性全部清理掉

```bash
# 批量删除相同特征的本地分支，分支跟踪删除用 git branch -r
git branch | grep feature | xargs git branch -d 
```