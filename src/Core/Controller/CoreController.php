<?php

namespace Core\Controller;

use Core\Entity\Image;
use Core\Service\CoreManager;
use Silex\Application;
use Symfony\Component\HttpFoundation\Response;

class CoreController
{
    /**
     * @var Application
     */
    protected $app;

    /**
     * @param Application $app
     */
    public function setApp(Application $app)
    {
        $this->app = $app;
    }

    /**
     * @return CoreManager
     */
    public function getCoreManager(): CoreManager
    {
        return $this->app['core.manager'];
    }

    /**
     * @param string $templateName
     *
     * @return Response
     */
    public function render(string $templateName): Response
    {
        ob_start();
        include(ROOT_DIR.'/src/Core/Views/'.$templateName.'.php');
        $body = ob_get_contents();
        ob_end_clean();

        return new Response($body);
    }

    /**
     * @param Image $image
     *
     * @return Response
     */
    public function generateImageResponse(Image $image): Response
    {
        $response = new Response();
        $response->setContent($image->getContent());
        $response = $this->setHeadersContent($image, $response);
        $image->unlinkUsedFiles();

        return $response;
    }

    /**
     * @param Image $image
     *
     * @return Response
     */
    public function generatePathResponse(Image $image): Response
    {
        $response = new Response();
        $imagePath = $image->getNewFileName();
        $imagePath = sprintf($this->app['flysystems']['file_path_resolver'], $imagePath);
        $response->setContent($imagePath);
        $image->unlinkUsedFiles();

        return $response;
    }

    /**
     * @param Image    $image
     * @param Response $response
     *
     * @return Response
     */
    protected function setHeadersContent(Image $image, Response $response): Response
    {
        $response->headers->set('Content-Type', $image->getResponseContentType());

        $expireDate = new \DateTime();
        $expireDate->add(new \DateInterval('P1Y'));
        $response->setExpires($expireDate);
        $longCacheTime = 3600 * 24 * ((int)$this->app['params']['header_cache_days']);

        $response->setMaxAge($longCacheTime);
        $response->setSharedMaxAge($longCacheTime);
        $response->setPublic();

        if ($image->getOptions()['refresh']) {
            $response->headers->set('Cache-Control', 'no-cache, private');
            $response->setExpires(null)->expire();

            $response->headers->set('im-identify', $this->app['image.processor']->getImageIdentity($image));
            $response->headers->set('im-command', $image->getCommandString());
        }

        return $response;
    }
}
