<?php
namespace App;
class ViewRenderer
{
    public function render(string $view, array $params, bool $isLayout):string
    {
        extract($params);
        ob_start();
        include $view;
        $start = ob_get_clean();
        if ($isLayout) {
            $content = file_get_contents('../views/layout.phtml');
            $content = str_replace('{content}',$start, $content);
            return $content;
        }
        return $start;
    }
}