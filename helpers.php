<?php

if (! function_exists('featica')) {
    /**
     * Featica helper.
     *
     * @return \Featica\FeaticaManager
     */
    function featica()
    {
        return \Featica\Featica::getFacadeRoot();
    }
}
