<?php
/**
 * Slim - a micro PHP 5 framework
 *
 * @author      Josh Lockhart
 * @author      Andrew Smith
 * @link        http://www.slimframework.com
 * @copyright   2013 Josh Lockhart
 * @version     0.1.0
 * @package     SlimViews
 *
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace smomoo\util;

use smomoo\util\App;

class TwigExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'smomoo';
    }

    public function getFunctions()
    {
        return array(
            'urlName' => new \Twig_Function_Function(array($this, 'urlName')),
            'active' => new \Twig_Function_Function(array($this, 'active'), array('is_safe' => array('html'))),
            'activeUrl' => new \Twig_Function_Function(array($this, 'activeUrl'), array('is_safe' => array('html'))),
        );
    }

    public function urlName()
    {
        return App::urlName();
    }

    public function active($isActive)
    {
        if ($isActive) {
            return ' class="active"';
        } else {
            return '';
        }
    }

    public function activeUrl($url)
    {
        return $this->active($this->urlName() == $url);
    }

}
