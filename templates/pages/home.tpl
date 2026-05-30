{extends file="layouts/main.tpl"}

{block name=content}
    <section class="page-hero">
        <h1>Блог</h1>
        <p class="lead">Последние публикации по категориям</p>
    </section>

    {foreach $categories as $category}
        <section class="category-block">
            <header class="category-block__header">
                <div>
                    <h2>{$category->name|escape}</h2>
                    <p>{$category->description|escape}</p>
                </div>
                <a class="btn btn--outline" href="{$app_url}/category/{$category->slug|escape}">Все статьи</a>
            </header>

        </section>
    {/foreach}
{/block}
