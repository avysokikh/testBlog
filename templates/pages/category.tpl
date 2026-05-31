{extends file="layouts/main.tpl"}

{block name=content}
    <section class="page-hero">
        <h1>{$category->name|escape}</h1>
        <p class="lead">{$category->description|escape}</p>
    </section>

    <div class="toolbar">
        <span class="toolbar__label">Сортировка:</span>
        <a class="toolbar__link {if $sort == 'date'}is-active{/if}"
           href="/category/{$category->slug|escape}?sort=date">По дате</a>
        <a class="toolbar__link {if $sort == 'views'}is-active{/if}"
           href="/category/{$category->slug|escape}?sort=views">По просмотрам</a>
    </div>
{/block}
