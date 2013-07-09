<?php

namespace StrSocial\Bundle\SmsQueueBundle\Command;

use StrSocial\Bundle\SmsQueueBundle\Component\Sender;

use Doctrine\Bundle\DoctrineBundle\Registry;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class DeliverBufferCommand extends ContainerAwareCommand
{
    protected function configure() {
        $this->setName('smsqueuebundle:buffer:deliver')
            ->setDescription('Deliver sms message stored in the buffer');
    }
    
    protected function execute(InputInterface $input, OutputInterface $output) {
        
        $logger = $this->getContainer()->get('logger');
        $logger->info('Starting sms delivery '.time());
        
        $total = $this->getContainer()->get('str_social_sms_queue.sender')->deliverBuffer();
        
        $logger->info('Successfully sent '.$total .' sms messsages');
    }
}
