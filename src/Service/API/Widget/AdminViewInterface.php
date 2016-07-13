<?php
namespace Sarcofag\Service\API\Widget;


interface AdminViewInterface
{
    /**
     * Return view content for
     * rendering in Admin area
     * 
     * @return string
     */
    public function getAdminView();
}
