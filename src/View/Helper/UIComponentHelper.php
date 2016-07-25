<?php
namespace Sarcofag\View\Helper;

use DI\FactoryInterface;
use Sarcofag\API\WP;
use Sarcofag\Exception\RuntimeException;
use Sarcofag\View\Renderer\RendererInterface;

class UIComponentHelper implements HelperInterface
{
    /**
     * @var WP
     */
    protected $wpService;

    /**
     * @var array
     */
    protected $plugins = [];

    /**
     * UIComponentHelper constructor.
     *
     * @param WP $wpService
     */
    public function __construct(WP $wpService, array $uiComponentPaths)
    {
        $this->wpService = $wpService;
        $this->plugins = [];
        $activePlugins = $wpService->get_option('active_plugins');
        
        foreach ($activePlugins as $activePlugin) {
            $pluginName = trim(dirname($activePlugin), '/');
            if (!array_key_exists($pluginName, $uiComponentPaths)) continue;

            $existsDir = [];
            foreach ($uiComponentPaths[$pluginName] as $key=>$path) {
                $pluginUIItemDir = WP_PLUGIN_DIR . '/' . $pluginName . '/' . $path;
                if (!is_dir($pluginUIItemDir)) continue;
                $existsDir[$pluginName.'/'.$key] = trim($wpService->plugin_dir_url($activePlugin), '/'). '/' . $path;
            }
            $this->plugins[$pluginName] = $existsDir;
        }
    }

    /**
     * @param array $arguments
     *
     * @return bool | string
     */
    public function invoke(array $arguments)
    {
        if (count($arguments) < 1) {
            throw new RuntimeException("You have to define module and type and name");
        }

        $templateDirectoryUri = $this->wpService->get_template_directory_uri();

        if (WP_DEBUG) {
            $requirePath = '/src/ui/vendor/requirejs/require.js';
            $requireConfigPath = '/src/ui/js/config.js';


            $this->wpService->wp_enqueue_script('requirejs', $templateDirectoryUri . $requirePath);
            $this->wpService->wp_enqueue_script('requirejsConfig', $templateDirectoryUri . $requireConfigPath);
        }

        foreach ($this->plugins as $plugin=>$pluginItems) {
            foreach ($pluginItems as $pluginItemKey => $pluginItemPath) {
                if (strpos($arguments[0], $pluginItemKey) === 0) {
                    $script = str_replace($pluginItemKey, $pluginItemPath , $arguments[0]);
                    $this->wpService->wp_enqueue_script('component'.stripslashes($arguments[0]), $script. '.js');
                    return;
                }
            }
        }
        
        if (WP_DEBUG) {
            $this->wpService->wp_enqueue_script('component'.stripslashes($arguments[0]),
                                                    $templateDirectoryUri . '/src/ui/js/' . $arguments[0] . '.js');
        } else {
            $this->wpService->wp_enqueue_script('component'.stripslashes($arguments[0]),
                                                    $templateDirectoryUri . '/js/' . $arguments[0] . '.js');
        }
    }

    /**
     * @param string $component
     * @param string $plugin
     */
    protected function includeFromPlugin($component, $plugin)
    {
        if (!array_key_exists($plugin, $this->plugins)) {
            throw new RuntimeException('Could not found plugin '.$plugin);
        }

        if (WP_DEBUG) {

        } else {
            $this->wpService->wp_enqueue_script('component'.ucfirst(stripslashes($component)),
                              $this->plugins[$plugin] . '/' . $component . '.js');
        }
    }

    /**
     * @param string $component
     */
    protected function includeFromCurrentTheme($component)
    {

    }
}
