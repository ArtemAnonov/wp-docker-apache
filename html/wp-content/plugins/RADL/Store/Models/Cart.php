<?php

namespace RADL\Store\Models;

class Cart extends BasedModelsWC
{
    public $apiType = '/wc/store/v1/';

    public $route_base = 'cart';

    public $type = 'cart';

}
