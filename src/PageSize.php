<?php


namespace antonyz89\pagesize;

use Yii;

/**
 * Trait Pagesize
 * @package antonyz89\pagesize
 *
 * @property-read $pageSize
 */
class PageSize
{
    protected static $_pagesize;

    /** @var string|integer */
    public static $defaultPageSize = '20';

    /** @var string|integer[] */
    public static $values = ['20', '50', '100'];

    public static function getPageSize(): string
    {
        return self::$_pagesize ?? (self::$_pagesize = Yii::$app->request->getQueryParam('pageSize', (string)self::$defaultPageSize));
    }

    /**
     * @param string $id
     * @return string
     */
    public static function options($id = 'pagesize')
    {
        $options = array_map(static function ($value) {
            return "<option value='$value'  " . (self::getPageSize() === (string)$value ? 'selected' : null) . ">$value</option>";
        }, self::$values);

        return "
        <select id='$id' name='pageSize'>
            <option value='0'  " . (self::getPageSize() === '0' ? 'selected' : null) . ">" . Yii::t('pagesize', 'All') . "</option>
            $options
        </select>
        ";
    }


}
