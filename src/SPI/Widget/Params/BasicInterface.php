<?php
namespace Sarcofag\SPI\Widget\Params;

interface BasicInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getId();

    /**
     * @return array
     */
    public function getOptions();
}
