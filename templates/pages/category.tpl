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

    {if $articles|@count == 0}
        <p class="empty-state">В этой категории пока нет статей.</p>
    {else}
        <div class="articles-grid">
            {foreach $articles as $article}
                {include file="components/article_card.tpl" article=$article}
            {/foreach}
        </div>

        {if $total_pages > 1}
            <nav class="pagination" aria-label="Пагинация">
                {if $page > 1}
                    <a class="pagination__link"
                       href="/category/{$category->slug|escape}?sort={$sort|escape}&page={$page - 1}">← Назад</a>
                {/if}

                <span class="pagination__info">Страница {$page} из {$total_pages}</span>

                {if $page < $total_pages}
                    <a class="pagination__link"
                       href="/category/{$category->slug|escape}?sort={$sort|escape}&page={$page + 1}">Вперёд →</a>
                {/if}
            </nav>
        {/if}
    {/if}
{/block}
