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

2 - Update your ActiveDataProvider on SearchModel:

```php
use antonyz89\pagesize\PageSize;

public function search($params)
{
    ...
    $dataProvider = new ActiveDataProvider([
        'query' => $query,
        'sort' => ['defaultOrder' => ['id' => SORT_DESC]],
        'pagination' => PageSize::getPageSize() === '0' ? false : ['pageSize' => PageSize::getPageSize()],
    ]);
    ...
}
```

3 - Add `panel -> footer` to your GridView:

```php
use antonyz89\pagesize\PageSize;

GridView::widget([
    ...
    'panelFooterTemplate' => '{footer}<div class="clearfix"></div>',
    'filterSelector' => '#pagesize',
    'panel' => [
        'footer' => "
            {pager} {summary}
            <div class='float-right'>
                " . PageSize::options() . "
            </div>
        "
    ],
    ...
]);
```

##Optional

1 - In your `common/config/main.php`, you can override default values:

```php
use antonyz89\pagesize\PageSize;

PageSize::$defaultPageSize = 10;
PageSize::$values = [10, 20, 30, 40, 50];
```

2 - Create a GridView for you! Avoid duplicate code.

**Tip:** Create your new component in `common/components`

```php
<?php

namespace common\components;

use Yii;
use antonyz89\pagesize\PageSize;

class GridView extends \yii\grid\GridView
{
    public $panelFooterTemplate = '{footer}<div class="clearfix"></div>';
    public $filterSelector = '#pagesize';

    protected function initPanel()
    {
        $this->panel['footer'] = "
            {pager} {summary}
            <div class='pull-right margin-r-5'>
                " . PageSize::options() . "
            </div>
        ";

        parent::initPanel();
    }
}
```
