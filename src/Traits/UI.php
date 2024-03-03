<?php

namespace Bfg\OpenDoc\Traits;

use DOMDocument;
use DOMException;

trait UI
{
    protected static int $index = 0;
    protected static int $tabIndex = 0;

    public function phpHeader(string $id, string $zone, string $name, string $description = null)
    {
        $phpDescription = $description ? "'description' => '$description'," : '' ;
        return <<<PHP
@php
    \Bfg\OpenDoc\Facades\OpenDoc::init([
        'id' => '$id',
        'zone' => '$zone',
        'name' => '$name',
        $phpDescription
    ]);
@endphp

PHP;
    }

    public function markdown(string $text)
    {
        return "@markdown('".str_replace("'", "\\'", $text)."')
        ";
    }

    public function pre($content) {
        return "<pre>$content</pre>";
    }

    public function nl()
    {
        $doc = new DOMDocument();
        $doc->formatOutput = true;
        $br = $doc->createElement("br");
        $doc->appendChild($br);
        return $doc->saveHTML();
    }

    public function createCard($content, $title = null) {
        $doc = new DOMDocument();
        $doc->formatOutput = true;

        $boxDiv = $doc->createElement("div");
        $boxDiv->setAttribute("class", "box");

        if ($title) {
            $h5 = $doc->createElement("h5", htmlspecialchars($title));
            $h5->setAttribute("class", "mb-10 text-medium");
            $h5->setAttribute("id", "lineicons");
            $boxDiv->appendChild($h5);
        }

        $exampleBoxDiv = $doc->createElement("div");
        $exampleBoxDiv->setAttribute("class", "example-box overflow-auto");
        $boxDiv->appendChild($exampleBoxDiv);

        $iconsWrapperDiv = $doc->createElement("div");
        $iconsWrapperDiv->setAttribute("class", "icons-wrapper");
        $exampleBoxDiv->appendChild($iconsWrapperDiv);

        // Загрузка HTML в 'icons-wrapper' div
        $contentFragment = $doc->createDocumentFragment();
        $contentFragment->appendXML("<![CDATA[" . $content . "]]>");
        $iconsWrapperDiv->appendChild($contentFragment);

        // Добавляем структуру в DOMDocument
        $doc->appendChild($boxDiv);

        // Возвращаем HTML
        return $doc->saveHTML($boxDiv);
    }

    public function createCodeBox($content) {
        $doc = new DOMDocument();
        $doc->formatOutput = true;

        // Создаем элемент <pre> и добавляем атрибуты
        $pre = $doc->createElement("pre");
        $pre->setAttribute("data-simplebar", "");
        $pre->setAttribute("class", "code-box");

        // Создаем кнопку для копирования
        $button = $doc->createElement("button", "Copy");
        $button->setAttribute("class", "copy-btn");
        $pre->appendChild($button);

        // Создаем элемент <code>
        $code = $doc->createElement("code");
        $pre->appendChild($code);

        // Вставляем HTML контент внутрь <code>
        $fragment = $doc->createDocumentFragment();
        // Важно! Этот контент должен быть корректным XML, для HTML используйте CDATA если есть не XML совместимые символы
        $fragment->appendXML("<![CDATA[" . $content . "]]>");
        $code->appendChild($fragment);

        // Добавляем готовую структуру к документу
        $doc->appendChild($pre);

        return $doc->saveHTML();
    }

    public function createBootstrapTabs($tabs) {
        $time = uniqid(time());
        $html = '<div class="tab-style-1"><ul class="nav nav-tabs" id="myTab-'.$time.'" role="tablist">';
        foreach ($tabs as $index => $tab) {
            $id = "id-" . $time . '-' . md5($tab['title']);
            $target = "target-" . $time . '-' . md5($tab['title']);
            $tabs[$index]['id'] = $id;
            $tabs[$index]['target'] = $target;
            $isActive = $index === 0 ? 'active' : '';
            $ariaSelected = $index === 0 ? 'true' : 'false';
            $disabled = isset($tab['disabled']) && $tab['disabled'] ? 'disabled' : '';
            $html .= "<li class=\"nav-item\" role=\"presentation\">";
            $html .= "<button class=\"nav-link $isActive\" id=\"{$id}\" data-bs-toggle=\"tab\" data-bs-target=\"#{$target}\" type=\"button\" role=\"tab\" aria-controls=\"{$target}\" aria-selected=\"$ariaSelected\" $disabled>{$tab['title']}</button>";
            $html .= "</li>";
        }
        $html .= '</ul>';

        $html .= '<div class="tab-content" id="myTabContent">';
        foreach ($tabs as $index => $tab) {
            $isActive = $index === 0 ? 'show active' : '';
            $html .= "<div class=\"tab-pane fade $isActive\" id=\"{$tab['target']}\" role=\"tabpanel\" aria-labelledby=\"{$tab['id']}\" tabindex=\"0\">{$tab['content']}</div>";
        }
        $html .= '</div></div>';

        return $html;
    }

    public function createBootstrapTable($data, $headers = []): bool|string
    {
        $doc = new DOMDocument();
        $doc->formatOutput = true;

        // Создание элемента <table> и добавление классов Bootstrap
        $table = $doc->createElement("table");
        $table->setAttribute("class", "table table-bordered table-hover");

        // Если заголовки предоставлены, добавляем их
        if (!empty($headers)) {
            $thead = $doc->createElement("thead");
            $tr = $doc->createElement("tr");
            foreach ($headers as $header) {
                $th = $doc->createElement("th", htmlspecialchars($header));
                $tr->appendChild($th);
            }
            $thead->appendChild($tr);
            $table->appendChild($thead);
        }

        // Создание тела таблицы
        $tbody = $doc->createElement("tbody");
        foreach ($data as $rowData) {
            $tr = $doc->createElement("tr");
            foreach ((array) $rowData as $cellData) {
                $td = $doc->createElement("td", $cellData ? $this->markdown($cellData) : '');
                $tr->appendChild($td);
            }
            $tbody->appendChild($tr);
        }
        $table->appendChild($tbody);

        // Добавление таблицы к документу
        $doc->appendChild($table);

        // Возврат сгенерированного HTML
        return $doc->saveHTML();
    }

    /**
     * @param $title
     * @param $message
     * @param  string  $type
     * @return bool|string
     * @throws DOMException
     */
    public function createAlertBox($title, $message, string $type = 'primary'): bool|string
    {
        $doc = new DOMDocument();

        $alertBoxDiv = $doc->createElement("div");
        $alertBoxDiv->setAttribute("class", "alert-box {$type}-alert");

        $alertDiv = $doc->createElement("div");
        $alertDiv->setAttribute("class", "alert");
        $alertBoxDiv->appendChild($alertDiv);

        $contentDiv = $doc->createElement("div");
        $contentDiv->setAttribute("class", "content");
        $alertDiv->appendChild($contentDiv);

        $h6 = $doc->createElement("h6", htmlspecialchars($title));
        $contentDiv->appendChild($h6);

        if ($message) {

            $p = $doc->createElement("p", $this->markdown($message));
            $contentDiv->appendChild($p);
        }

        $doc->appendChild($alertBoxDiv);

        return $doc->saveHTML($doc->documentElement);
    }
}
