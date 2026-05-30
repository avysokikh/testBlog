create table articles
(
    id           int unsigned auto_increment
        primary key,
    image        varchar(500) default ''                not null,
    title        varchar(255)                           not null,
    description  text                                   not null,
    body         longtext                               not null,
    views        int unsigned default '0'               not null,
    published_at datetime                               not null,
    created_at   timestamp    default CURRENT_TIMESTAMP null
)
    collate = utf8mb4_unicode_ci;

create index idx_articles_published_at
    on articles (published_at);

create index idx_articles_views
    on articles (views);

create table categories
(
    id          int unsigned auto_increment
        primary key,
    name        varchar(255)                        not null,
    description text                                not null,
    slug        varchar(255)                        not null,
    created_at  timestamp default CURRENT_TIMESTAMP null,
    constraint slug
        unique (slug)
)
    collate = utf8mb4_unicode_ci;

create table article_category
(
    article_id  int unsigned not null,
    category_id int unsigned not null,
    primary key (article_id, category_id),
    constraint fk_ac_article
        foreign key (article_id) references articles (id)
            on delete cascade,
    constraint fk_ac_category
        foreign key (category_id) references categories (id)
            on delete cascade
)
    collate = utf8mb4_unicode_ci;

create index idx_ac_category
    on article_category (category_id);

