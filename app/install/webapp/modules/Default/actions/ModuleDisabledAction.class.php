<?php
class ModuleDisabledAction extends Action
{
    public function execute ()
    {
    }

    public function getDefaultView ()
    {
        return View::SUCCESS;
    }


    public function getRequestMethods ()
    {
        return Request::NONE;

    }

}

?>