# index.twig

```twig
{# 延伸 base.twig #}
{% extends "base.twig" %}

{# 頁面標題 #}
{% block title %}主頁{% endblock title %}

{# html頭 #}
{% block head %}
{{ parent() }}
<style>
  span.text-muted {
    display: inline-block;
  }
</style>
{% endblock head %}

{# 主頁內容 #}
{% block content %}

<section class="bg-light p-4 rounded">

  <form action="/discovery" method="get" id="searchForm">
    <div class="mb-3">
      <label for="searchBar" class="form-label">搜尋書目：</label>
      <input type="text" name="search" id="searchBar" class="form-control" placeholder="輸入檢索詞">
    </div>
    <button type="submit" class="btn btn-primary">送出</button>
  </form>
</section>

<section class="bg-body row  overflow-hidden">
  <div class="col-4 g-4">
    <div class="container py-3 bg-light rounded mb-4">
      <h3 class="fw-lighter">最新消息</h3>
      <ul class="list-unstyled">
        {% for post in news %}
        <li class="text-wrap mb-2">{{ post.Content }} <span class="text-muted">（{{ post.PublishDate }}）</li>
        {% endfor %}
      </ul>
    </div>
    <div class="container py-3 bg-light rounded mb-4">
      Lorem ipsum dolor sit amet consectetur adipisicing elit. Modi ipsam tempora excepturi deleniti assumenda, eius
      quibusdam quaerat aliquam esse iusto adipisci sunt commodi rem praesentium saepe nam repudiandae suscipit. Nam?
      Consequatur repellendus debitis expedita ratione inventore voluptatum reiciendis natus voluptate, cupiditate,
      assumenda, odio sapiente? Quae vero eligendi dolore. Soluta animi cupiditate quaerat deleniti perspiciatis hic
      eveniet similique distinctio error fugit.
    </div>
  </div>
  <div class="col-8 g-4">
    <div class="container py-3 bg-light rounded mb-4">
      <h3 class="fw-lighter">系統維護公告</h3>
      <ul class="list-unstyled">
        {% for post in maintain %}
        <li class="mb-2 text-wrap">{{ post.Content }} <span class="text-muted">（{{ post.PublishDate }}）</span></li>
        {% endfor %}
      </ul>
    </div>
    <div class="container py-3 bg-light rounded mb-4">
      <h3 class="fw-lighter">更新日誌</h3>
      <ul class="list-unstyled">
        {% for post in updates %}
        <li class="mb-2 text-wrap">{{ post.Content }} <span class="text-muted">（{{ post.PublishDate }}）</span></li>
        {% endfor %}
      </ul>
    </div>

  </div>

</section>
{% endblock %}

{% block extraScript %}
{{ parent() }}
{% endblock extraScript %}
{# html腳 #}
{% block footer %}
{{ parent() }}
{% endblock footer %}
```