<?php

namespace Assetic\Asset;

use Assetic\AssetManager;
use Assetic\Filter\FilterCollection;
use Assetic\Filter\FilterInterface;

/*
 * This file is part of the Assetic package.
 *
 * (c) Kris Wallsmith <kris.wallsmith@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * A reference to an asset in the asset manager.
 *
 * @author Kris Wallsmith <kris.wallsmith@gmail.com>
 */
class AssetReference implements AssetInterface
{
    private $am;
    private $name;
    private $filters;

    public function __construct(AssetManager $am, $name)
    {
        $this->am = $am;
        $this->name = $name;
        $this->filters = new FilterCollection();
    }

    public function ensureFilter(FilterInterface $filter)
    {
        $this->filters->ensure($filter);
    }

    public function getFilters()
    {
        $this->flushFilters();

        return $this->callAsset(__FUNCTION__);
    }

    public function load(FilterInterface $additionalFilter = null)
    {
        $this->flushFilters();

        return $this->callAsset(__FUNCTION__, array($additionalFilter));
    }

    public function dump($targetUrl = null, FilterInterface $additionalFilter = null)
    {
        $this->flushFilters();

        return $this->callAsset(__FUNCTION__, array($additionalFilter));
    }

    public function getContent()
    {
        return $this->callAsset(__FUNCTION__);
    }

    public function setContent($content)
    {
        $this->callAsset(__FUNCTION__, array($content));
    }

    public function getSourceUrl()
    {
        return $this->callAsset(__FUNCTION__);
    }

    public function getLastModified()
    {
        return $this->callAsset(__FUNCTION__);
    }

    // private

    private function callAsset($method, $arguments = array())
    {
        $asset = $this->am->get($this->name);

        return call_user_func_array(array($asset, $method), $arguments);
    }

    private function flushFilters()
    {
        $asset = $this->am->get($this->name);

        $asset->ensureFilter($this->filters);
        $this->filters = new FilterCollection();
    }
}
