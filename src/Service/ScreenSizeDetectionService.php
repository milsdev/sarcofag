<?php
namespace Sarcofag\Service;

use DI\FactoryInterface;

class ScreenSizeDetectionService
{
    /**
     * @var \Mobile_Detect
     */
    protected $mobileDetectService;

    /**
     * ScreenSizeDetectionService constructor.
     *
     * @param FactoryInterface $factory
     * @param \Mobile_Detect $mobileDetectService
     */
    public function __construct(FactoryInterface $factory,
                                \Mobile_Detect $mobileDetectService)
    {
        $this->factory = $factory;
        $this->mobileDetectService = $mobileDetectService;
    }


    /**
     * Return type of the screen,
     * or screen size
     *
     * @return string
     */
    public function which()
    {
        return ($this->isLarge() ? 'large' :
                    ($this->isSmall() ? 'small' :
                        ($this->isMedium() ? 'medium' : 'large')));
    }

    /**
     * Return true if user screen is large
     *
     * @return bool
     */
    public function isLarge()
    {
        return !$this->mobileDetectService->isMobile() &&
               !$this->mobileDetectService->isTablet();
    }

    /**
     * Return true if user screen is large
     *
     * @return bool
     */
    public function isSmall()
    {
        return $this->mobileDetectService->isMobile() && !$this->mobileDetectService->isTablet();
    }

    /**
     * Return true if user screen is large
     *
     * @return bool
     */
    public function isMedium()
    {
        return $this->mobileDetectService->isTablet();
    }
}
