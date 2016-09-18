<?php
/**
 * Sarcofag (http://sarcofag.com)
 *
 * @link       https://github.com/milsdev/sarcofag
 * @copyright  Copyright (c) 20012-2016 Mil's (http://www.mils.agency)
 * @license    http://sarcofag.com/license/mit
 */
namespace SarcofagTest\View\Renderer;

use DI\FactoryInterface;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use org\bovigo\vfs\vfsStreamFile;
use org\bovigo\vfs\vfsStreamWrapper;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Sarcofag\API\WP;
use Sarcofag\SPI\EventManager\GenericListener;
use Sarcofag\Utility\Closure;
use Sarcofag\View\Helper\HelperManagerInterface;
use Sarcofag\View\Renderer\SimpleRenderer;

/**
 * Test suite for testing SimpleRenderer class
 *
 * @covers \Sarcofag\View\Renderer\SimpleRenderer
 */
class SimpleRendererTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $closureMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $helperManagerMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $factoryMock;

    /**
     * @var PHPUnit_Framework_MockObject_MockObject
     */
    private $wpMock;

    /**
     * @var string
     */
    private $stubContent;

    /**
     * @var string
     */
    private $stubPath;

    /**
     * @var string
     */
    private $stubTemplate;

    /**
     * @var array
     */
    private $stubArgs;

    /**
     * @var array
     */
    private $stubClosure;

    /**
     * @return void
     */
    public function setUp()
    {
        $this->closureMock = $this->getMock(Closure::class, ['bindTo'], [], '', false);
        $this->helperManagerMock = $this->getMockBuilder(HelperManagerInterface::class)->getMock();
        $this->factoryMock = $this->getMock(FactoryInterface::class);
        $this->wpMock = $this->getMock(WP::class);

        $this->stubContent = '<h1>Some HTML</h1>';
        $this->stubPath = '/full/path/to/test.phtml';
        $this->stubTemplate = 'test.phtml';
        $this->stubArgs = ['args'=>1];

        $this->stubClosure = function ($path, $args) {
            $this->assertEquals($this->stubPath, $path);
            $this->assertEquals($this->stubArgs, $args);
            echo $this->stubContent;
        };
    }

    /**
     * Testcase which should assert if all steps
     * are accomplished and content gathered and
     * returned expectedly
     *
     * @return void
     */
    public function testJustRenderingSteps()
    {
        $this->closureMock->expects($this->once())
             ->method('bindTo')
             ->with(
                 $this->helperManagerMock,
                 get_class($this->helperManagerMock)
             )
             ->willReturn($this->stubClosure);

        $this->factoryMock->expects($this->once())
                    ->method('make')
                    ->with(
                        Closure::class,
                        ['closure'=>function () {
                        }]
                    )
                    ->willReturn($this->closureMock);

        $renderSimpleMock = $this->getMock(
            SimpleRenderer::class,
            ['getTemplatePath'],
            [$this->helperManagerMock, [], $this->factoryMock, $this->wpMock]
        );

        $renderSimpleMock->expects($this->once())
                         ->method('getTemplatePath')
                         ->with($this->stubTemplate)
                         ->willReturn($this->stubPath);

        $result = $renderSimpleMock->render($this->stubTemplate, $this->stubArgs);
        $this->assertEquals($this->stubContent, $result);
    }

    /**
     * Test is response method in SimpleRenderer will just fullfill body of
     * the response and return RESPONSE.
     *
     * @return void
     */
    public function testJustResponseFullfilled()
    {
        $renderSimpleMock = $this->getMock(
            SimpleRenderer::class,
            ['render'],
            [$this->helperManagerMock, [], $this->factoryMock, $this->wpMock]
        );

        $renderSimpleMock->expects($this->once())
                         ->method('render')
                         ->with($this->stubPath, $this->stubArgs)
                         ->willReturn($this->stubContent);

        $responseMock = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $streamMock = $this->getMockBuilder(StreamInterface::class)->getMock();

        $responseMock->expects($this->once())->method('getBody')->willReturn($streamMock);
        $streamMock->expects($this->once())->method('write')->with($this->stubContent);

        $this->assertEquals(
            $responseMock,
            $renderSimpleMock->response(
                $responseMock,
                $this->stubPath,
                $this->stubArgs
            )
        );
    }

    /**
     * Test is alias will be converted to the full path
     * then requesting template with relative path
     *
     * @return void
     */
    public function testIsTemplatePathCorrectlyTransformed()
    {
        vfsStreamWrapper::register();

        $streamDir = new vfsStreamDirectory('superpupper');
        $streamDir->addChild(new vfsStreamFile('test.phtml'));
        vfsStreamWrapper::setRoot($streamDir);

        $this->stubPath = vfsStream::url('superpupper/test.phtml');

        $this->closureMock->method('bindTo')->willReturn($this->stubClosure);
        $this->factoryMock->method('make')->willReturn($this->closureMock);

        $renderSimpleMock = new SimpleRenderer(
            $this->helperManagerMock,
            [ 'view' => vfsStream::url('superpupper') ],
            $this->factoryMock,
            $this->wpMock
        );

        $result = $renderSimpleMock->render('view/test.phtml', $this->stubArgs);
    }

    /**
     * Test if render method and response methods can be called without
     * template args, and in this case will be used default empty array
     *
     * @return void
     */
    public function testIfTemplateArgsOptionalAndEmptyArrayByDefault()
    {

        $this->closureMock->expects($this->once())
                          ->method('bindTo')
                          ->with(
                              $this->helperManagerMock,
                              get_class($this->helperManagerMock)
                          )
                          ->willReturn($this->stubClosure);

        $this->factoryMock->expects($this->once())
                          ->method('make')
                          ->with(
                              Closure::class,
                              ['closure'=>function () {
                              }]
                          )
                          ->willReturn($this->closureMock);

        $renderSimpleMock = $this->getMock(
            SimpleRenderer::class,
            ['getTemplatePath'],
            [$this->helperManagerMock, [], $this->factoryMock, $this->wpMock]
        );

        $renderSimpleMock->expects($this->once())
                         ->method('getTemplatePath')
                         ->with($this->stubTemplate)
                         ->willReturn($this->stubPath);

        $this->stubArgs = [];
        $result = $renderSimpleMock->render($this->stubTemplate);

        $renderSimpleMock = $this->getMock(
            SimpleRenderer::class,
            ['render'],
            [$this->helperManagerMock, [],
            $this->factoryMock,
            $this->wpMock]
        );

        $responseMock = $this->getMockBuilder(ResponseInterface::class)->getMock();
        $streamMock = $this->getMockBuilder(StreamInterface::class)->getMock();

        $responseMock->expects($this->once())->method('getBody')->willReturn($streamMock);
        $streamMock->expects($this->once())->method('write');

        $renderSimpleMock->response($responseMock, $this->stubPath);
    }
}
