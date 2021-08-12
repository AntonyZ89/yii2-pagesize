yii2-pagesize
===================

<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YATHVT293SXDL&source=url">
  <img src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_LG.gif" alt="Donate with PayPal" />
</a>

--

[![Latest Stable Version](https://poser.pugx.org/antonyz89/yii2-pagesize/v/stable)](https://packagist.org/packages/antonyz89/yii2-pagesize)
[![Total Downloads](https://poser.pugx.org/antonyz89/yii2-pagesize/downloads)](https://packagist.org/packages/antonyz89/yii2-pagesize)
[![Latest Unstable Version](https://poser.pugx.org/antonyz89/yii2-pagesize/v/unstable)](https://packagist.org/packages/antonyz89/yii2-pagesize)
[![License](https://poser.pugx.org/antonyz89/yii2-pagesize/license)](https://packagist.org/packages/antonyz89/yii2-pagesize)

- [Installation](#installation)
- [Usage](#usage)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist antonyz89/yii2-pagesize dev-master
```

or add

```
"antonyz89/yii2-pagesize": "dev-master"
```

to the require section of your `composer.json` file.

## USAGE

1 - Add the translation:

`common/config/main.php`

```php
return [
    ...
    'components' => [
        'i18n' => [
            'translations' => [
                'pagesize' => [
                    'class' => \yii\i18n\PhpMessageSource::class,
                    'basePath' => '@antonyz89/pagesize/messages',
                ]
            ]
        ]
    ],
    ...
];
```

2 - Add `panel -> footer` to your GridView:

```php
use antonyz89\pagesize\PageSize;

$pageSize = PageSize::widget([
    'options' => [
        'id' => 'per-page' // without #
    ]
]);

GridView::widget([
    ...
    'panelFooterTemplate' => '{footer}<div class="clearfix"></div>',
    'filterSelector' => '#per-page', // with #
    'panel' => [
        'footer' => "
            {pager} {summary}
            <div class='float-right'>
                $pageSize
            </div>
        "
    ],
    ...
]);
```

## Optional

1 - In your `common/config/bootstrap.php`, you can override default values:

```php
use antonyz89\pagesize\PageSize;

PageSize::$defaultPageSize = 10;
PageSize::$values = [10, 20, 30, 40, 50];

/* `PageSize::$renderItem` to being used in `$renderSelect` */
PageSize::$renderItem = static function ($value, $key, $page) {
    return [$key, $value];
};

/*
 * `PageSize::$renderSelect`, use for render a custom select.
 * If needed override $renderItem to return `$items` as you want
 */
PageSize::$renderSelect = static function (array $options, array $items, string $pageSize) {
    $items = array_combine(
        array_map(static function ($value) {
            return $value[0];
        }, $items),
        array_map(static function ($value) {
            return $value[1];
        }, $items)
    );


    return Select2::widget([
        'name' => $options['name'],
        'id' => $options['id'],
        'data' => $items,
        'value' => $pageSize,
        'hideSearch' => true,
        'theme' => Select2::THEME_MATERIAL
    ]);
};
```

2 - Create a GridView for you! Avoid duplicate code.

**Tip:** Create your new component in `common/components`

```php
<?php

namespace common\components;

use antonyz89\pagesize\PageSize;

class GridView extends \yii\grid\GridView
{
    public $panelFooterTemplate = '{footer}<div class="clearfix"></div>';
    public $filterSelector = '#pagesize';

    protected function initPanel()
    {
        $pageSize = PageSize::widget([
            'options' => [
                'id' => str_replace('#', '', $this->filterSelector) // without #
            ]
        ]);
    
        $this->panel['footer'] = "
            {pager} {summary}
            <div class='float-right'>
                $pageSize
            </div>
        ";

        parent::initPanel();
    }
}
```

## Use custom pagesize ID

1 - Update your ActiveDataProvider on SearchModel:

```php
use antonyz89\pagesize\PageSizeTrait;

public class ExampleSearch extends Example {
    use PageSizeTrait; // add PageSizeTrait
    
    public $pageSizeId = 'custom-pagesize'; // custom ID

    public function search($params)
    {
        ...
        $dataProvider = new ActiveDataProvider([
            ...
            'pagination' => $this->pagination, // add `$this->pagination`
        ]);
        ...
    }
}
```

2 - Add `panel -> footer` to your GridView:

```php
use antonyz89\pagesize\PageSize;

$pageSize = PageSize::widget([
    'options' => [
        'id' => 'custom-pagesize' // without #
    ]
]);

GridView::widget([
    ...
    'panelFooterTemplate' => '{footer}<div class="clearfix"></div>',
    'filterSelector' => '#custom-pagesize', // with #
    'panel' => [
        'footer' => "
            {pager} {summary}
            <div class='float-right'>
                $pageSize
            </div>
        "
    ],
    ...
]);
```

2.1 - If you created your own `GridView`, you just need to override `filterSelector`:

```php
use my\own\GridView;


GridView::widget([
    ...
    'filterSelector' => '#custom-pagesize', // with #
    ...
]);
```
