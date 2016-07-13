<?php
namespace Sarcofag\View\Helper;

use Sarcofag\Service\ScreenSizeDetectionService;

class ScreenSizeDetectionHelper implements HelperInterface
{
    /**
     * @var ScreenSizeDetectionService
     */
    protected $screenSizeDetectionService;

    /**
     * ScreenSizeDetectionHelper constructor.
     *
     * @param ScreenSizeDetectionService $screenSizeDetectionService
     */
    public function __construct(ScreenSizeDetectionService $screenSizeDetectionService)
    {
        $this->screenSizeDetectionService = $screenSizeDetectionService;
    }

    /**
     * @param array $arguments
     * @return bool | string
     */
    public function invoke(array $arguments)
    {
        return $this;
    }

    /**
     * @return bool
     */
    public function medium()
    {
        return $this->screenSizeDetectionService->isMedium();
    }

    /**
     * @return bool
     */
    public function large()
    {
        return $this->screenSizeDetectionService->isLarge();
    }

    /**
     * @return string
     */
    public function which()
    {
        return $this->screenSizeDetectionService->which();
    }

    /**
     * @return bool
     */
    public function small()
    {
        return $this->screenSizeDetectionService->isSmall();
    }
}
