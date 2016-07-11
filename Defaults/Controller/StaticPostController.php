<?php
namespace Sarcofag\Defaults\Controller;

use Psr\Http\Message\ResponseInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class StaticPostController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return ResponseInterface
     */
    public function __invoke(Request $request,
                             Response $response,
                             array $args)
    {
        $response->getBody()
                 ->write(apply_filters( 'the_content', $args['requestedEntity']->post_content ));
    }
}
