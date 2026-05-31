{extends file="layouts/main.tpl"}

{block name=content}
    <article class="article-full">
        {if $article->image}
            <img class="article-full__image" src="{$app_url}{$article->image|escape}" alt="{$article->title|escape}">
        {/if}

        <header class="article-full__header">
            <h1>{$article->title|escape}</h1>
            <p class="article-full__description">{$article->description|escape}</p>
            <div class="article-full__meta">
                <time datetime="{$article->publishedAt->format('d.m.Y')}">{$article->publishedAt->format('d.m.Y')}</time>
                <span>{$article->views|escape} просмотров</span>
            </div>
        </header>

        <div class="article-full__body">
            {$article->body nofilter}
        </div>
    </article>
{/block}
