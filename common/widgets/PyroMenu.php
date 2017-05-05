<?php

namespace common\widgets;


use Yii;
use yii\widgets\Menu;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\models\helpers\YiiHelpers as Html;

class PyroMenu extends Menu{

    /**
     * @var bool whether to automatically activate items according to whether their route setting
     * matches the currently requested route.
     * @see isItemActive()
     */
    public $activateItems = true;
    /**
     * @var bool whether to activate parent menu items when one of the corresponding child menu items is active.
     * The activated parent menu items will also have its CSS classes appended with [[activeCssClass]].
     */
    public $activateParents = true;

    public $divider = false;

    public function run(){
        \yii\bootstrap\BootstrapPluginAsset::register($this->getView());
        parent::run();
    }

    /**
     * Renders the content of a menu item.
     * Note that the container and the sub-menus are not rendered here.
     * @param array $item the menu item to be rendered. Please refer to [[items]] to see what data might be in the item.
     * @return string the rendering result
     */
    protected function renderItem($item)
    {
    	$url = ArrayHelper::getValue($item,'url');
    	$label = ArrayHelper::getValue($item,'label','');
    	$options = ArrayHelper::getValue($item,'linkOptions',[]);
    	return Html::a($label,$url,$options);
    }

    /**
     * Recursively renders the menu items (without the container tag).
     * @param array $items the menu items to be rendered recursively
     * @return string the rendering result
     */
    protected function renderItems($items)
    {
        $n = count($items);
        $lines = [];
        foreach ($items as $i => $item) {
            $options = array_merge($this->itemOptions, ArrayHelper::getValue($item, 'containerOptions', []));
            $tag = ArrayHelper::remove($options, 'tag', 'li');
            $class = [];
            if ($item['active']) {
                $class[] = $this->activeCssClass;
            }
            if ($i === 0 && $this->firstItemCssClass !== null) {
                $class[] = $this->firstItemCssClass;
            }
            if ($i === $n - 1 && $this->lastItemCssClass !== null) {
                $class[] = $this->lastItemCssClass;
            }
            if (!empty($class)) {
                if (empty($options['class'])) {
                    $options['class'] = implode(' ', $class);
                } else {
                    $options['class'] .= ' ' . implode(' ', $class);
                }
            }
            $menu = $this->renderItem($item);
            if (!empty($item['items'])) {
                $submenuTemplate = ArrayHelper::getValue($item, 'submenuTemplate', $this->submenuTemplate);
                $menu .= strtr($submenuTemplate, [
                    '{items}' => $this->renderItems($item['items']),
                ]);
            }
            $lines[] = Html::tag($tag, $menu, $options);
        }
        $glue = ($this->divider)?'<li class="divider"></li>'.PHP_EOL:PHP_EOL;
        return implode($glue, $lines);
    }
}
/*

    <li><a href="#">Single Link</a></li>
    <li class="has-dropdown"><a href="#">Dropdown</a>
        <ul class="subnav">
            <li><a href="#">Example</a></li>
            <li><a href="#">Example</a></li>
            <li><a href="#">Example</a></li>
            <li><a href="#">Example</a></li>
        </ul>
    </li>
    <li class="has-dropdown"><a href="#">Half Width</a>
        <div class="subnav subnav-halfwidth">
            <div class="col-sm-6">
                <h6 class="alt-font">Subnav Title</h6>
                <ul class="subnav">
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                </ul>
            </div>

            <div class="col-sm-6">
                <h6 class="alt-font">Subnav Title</h6>
                <ul class="subnav">
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                </ul>
            </div>
        </div>
    </li>
    <li class="has-dropdown"><a href="#">Fullwidth</a>
        <div class="subnav subnav-fullwidth">
            <div class="col-md-3">
                <h6 class="alt-font">Subnav Title</h6>
                <ul class="subnav">
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                </ul>
            </div>

            <div class="col-md-3">
                <h6 class="alt-font">Subnav Title</h6>
                <ul class="subnav">
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                </ul>
            </div>

            <div class="col-md-3">
                <h6 class="alt-font">Subnav Title</h6>
                <ul class="subnav">
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                </ul>
            </div>

            <div class="col-md-3">
                <h6 class="alt-font">Subnav Title</h6>
                <ul class="subnav">
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                    <li><a href="#">Example</a></li>
                </ul>
            </div>
        </div>
    </li>

*/
