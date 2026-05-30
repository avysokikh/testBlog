<?php

declare(strict_types=1);

namespace App;

use Smarty\Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct(array $smartyConfig, private readonly string $appUrl)
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($smartyConfig['template_dir']);
        $this->smarty->setCompileDir($smartyConfig['compile_dir']);
        $this->smarty->setCacheDir($smartyConfig['cache_dir']);
        $this->smarty->escape_html = true;
    }

    public function render(string $template, array $data = []): void
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        $this->smarty->assign('app_url', $this->appUrl);
        $this->smarty->display($template);
    }
}
