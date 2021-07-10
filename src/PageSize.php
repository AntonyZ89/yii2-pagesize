<?php


namespace antonyz89\pagesize;

use Exception;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class PageSize
 * @package antonyz89\pagesize
 *
 * @property-read $pageSize
 */
class PageSize extends Widget
{
    protected $_pageSize;

    public $options = [];

    /** @var string|integer */
    public static $defaultPageSize = '20';

    /** @var string|integer[] */
    public static $values = ['20', '50', '100'];

    /** @var callable|null */
    public static $renderItem;

    /** @var callable|null */
    public static $renderSelect;

    /**
     * @inheritDoc
     * @throws Exception
     */
    public function init()
    {
        parent::init();

        $this->options['id'] = ArrayHelper::getValue($this->options, 'id', $this->id);
        $this->setId($this->options['id']);
        $this->options['name'] = ArrayHelper::getValue($this->options, 'name', str_replace('#', '', $this->id));

        if (!self::$renderItem) {
            self::$renderItem = static function ($value, $key, $page) {
                return Html::renderSelectOptions($page, [$key => $value]);
            };

            self::$renderSelect = static function ($options, $items) {
                return Html::tag(
                    'select',
                    implode("\n", $values),
                    $options
                );
            };
        }
    }

    public function getPageSize(): string
    {
        return $this->_pageSize ?? ($this->_pageSize = Yii::$app->request->getQueryParam($this->id, (string)self::$defaultPageSize));
    }

    public function registerJs()
    {

        $var = 'select_' . preg_replace("/[\s-]/", '_', trim($this->id));

        $js = /* @lang JavaScript */"
$(document).on('pjax:success', '[data-pjax-container]', function() {
    const selects = $('[data-krajee-select2]');

    selects.each(function () {
        const self = $(this);
        self.select2('destroy');
        self.select2(window[self.attr('data-krajee-select2')]);
        self.siblings('.kv-plugin-loading').remove();
    });
  }
);
";

        $this->view->registerJs($js);
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        $values = array_combine(self::$values, self::$values);

        $values[0] = Yii::t('pagesize', 'All');

        ksort($values);

        array_walk($values, function (&$value, $key) {
            $value = (self::$renderItem)($value, $key, $this->pageSize);
        });

        $this->registerJs();

        echo call_user_func(self::$renderSelect, $this->options, $values, $this->pageSize);
    }
}
