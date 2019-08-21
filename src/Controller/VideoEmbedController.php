<?php

namespace App\Controller;

use App\Components\VideoEmbed\VideoEmbedManager;
use App\Components\VideoEmbed\VideoEmbedRenderer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class VideoEmbedController extends AbstractController
{
    /**
     * @Route("/api/embed_video", name="embed_video_api", defaults={"_format"="json"})
     */
    public function api(VideoEmbedManager $embedManager, VideoEmbedRenderer $embedRender, Request $request)
    {
        $input = $request->query->get('input');
        $arr = [
      'status' => false,
    ];
        if ($embedManager->checkProviderInput($input)) {
            $twig = $embedRender->render($input);
            $arr['status'] = true;
            $arr['html'] = $twig;
        }
        $jsonEncode = new JsonEncode();

        return new Response($jsonEncode->encode($arr, 'json'));
    }
}
