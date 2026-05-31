{extends file="layouts/main.tpl"}

{block name=content}
    <article class="article-full">
        {if $article->image}
            <img class="article-full__image" src="{$article->image|escape}" alt="{$article->title|escape}">
        {/if}

        <header class="article-full__header">
            <h1>{$article->title|escape}</h1>
            <p class="article-full__description">{$article->description|escape}</p>
            <div class="article-full__meta">
                <time datetime="{$article->publishedAt->format('d.m.Y')}">{$article->publishedAt->format('d.m.Y')}</time>
                <span>{$article->views|escape} просмотров</span>
            </div>
            {if $article->categories|@count > 0}
                <div class="article-full__categories">
                    {foreach $article->categories as $cat}
                        <a class="tag" href="/category/{$cat->slug|escape}">{$cat->name|escape}</a>
                    {/foreach}
                </div>
            {/if}
        </header>

        <div class="article-full__body">
            {$article->body nofilter}
        </div>
    </article>

    {if $similar|@count > 0}
        <section class="similar-block">
            <h2>Похожие статьи</h2>
            <div class="articles-grid articles-grid--compact">
                {foreach $similar as $article}
                    {include file="components/article_card.tpl" article=$article}
                {/foreach}
            </div>
        </section>
    {/if}
{/block}
