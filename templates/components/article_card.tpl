<article class="article-card">
    <a class="article-card__image-link" href="/article/{$article->id}">
        {if $article->image}
            <img class="article-card__image" src="{$article->image|escape}" alt="{$article->title|escape}" loading="lazy">
        {else}
            <div class="article-card__placeholder"></div>
        {/if}
    </a>
    <div class="article-card__body">
        <h3 class="article-card__title">
            <a href="/article/{$article->id}">{$article->title|escape}</a>
        </h3>
        <p class="article-card__description">{$article->description|escape}</p>
        {if isset($article->categories) && $article->categories|@count > 0}
            <div class="article-card__categories">
                {foreach $article->categories as $cat}
                    <a class="tag tag--sm" href="/category/{$cat->slug|escape}">{$cat->name|escape}</a>
                {/foreach}
            </div>
        {/if}
        <div class="article-card__meta">
            <time datetime="{$article->publishedAt->format('Y-m-d')}">{$article->publishedAt->format('d.m.Y')}</time>
            <span class="article-card__views">{$article->views|escape} просмотров</span>
        </div>
    </div>
</article>
