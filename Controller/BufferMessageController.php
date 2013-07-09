<?php

namespace StrSocial\Bundle\SmsQueueBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use StrSocial\Bundle\SmsQueueBundle\Entity\BufferMessage;

/**
 * BufferMessage controller.
 *
 * @Route("/dev/sms/buffermessage")
 */
class BufferMessageController extends Controller
{
    /**
     * Lists all BufferMessage entities.
     *
     * @Route("/", name="dev_sms_buffermessage")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('StrSocialSmsQueueBundle:BufferMessage')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a BufferMessage entity.
     *
     * @Route("/{id}/show", name="dev_sms_buffermessage_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('StrSocialSmsQueueBundle:BufferMessage')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find BufferMessage entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

}
