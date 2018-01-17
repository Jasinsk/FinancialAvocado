<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
        ));
    }

    /**
 * @Route("/about-us", name="about_us")
 */
    public function aboutUsAction()
    {
        return $this->render('default/about-us.html.twig');
    }

    /**
     * @Route("/chat", name="chat")
     */
    public function chatAction()
    {
        return $this->render('default/chat.html.twig');
    }

    public function styleChange($theme)
    {
        if ($theme) {
            $theme = False;
        }
        else{
            $theme = True;
        }
        return $theme;
    }
}
