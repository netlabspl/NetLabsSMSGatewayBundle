<?php

/*
 * This file is part of the NetLabsSMSGatewayBundle package.
 *
 * (c) Michał Jabłoński <m.jablonski@net-labs.pl>
 */

namespace NetLabs\SMSGatewayBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author Michał Jabłoński <m.jablonski@net-labs.pl>
 */
class SmsTestCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('sms:test')
            ->setDescription('Sends test SMS message')
            ->addArgument('phone-number', InputArgument::REQUIRED, "Receiver's phone number")
            ->addArgument('message', InputArgument::REQUIRED, 'Message')
            ->addArgument('senderName', InputArgument::OPTIONAL, "Sender's name");
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Sending test message...');

        $smsService = $this->getContainer()->get('net_labs_sms');

        $smsService->setIsTest(true);

        $message = $smsService->compose()
            ->setReceiver($input->getArgument('phone-number'))
            ->setContent($input->getArgument('message'))
            ->setIdentifier(uniqid());

        if ($input->getArgument('senderName')) {
            $message->setSenderName($input->getArgument('senderName'));
        }

        $result = $smsService->send($message);

//        $result = $smsService->receive(false);

//        $result = $smsService->getReport(null, null, null, 'e9fd98eb96');
//        $result = $smsService->getReport(null, null, null, null, 'testId1');

//        $result = $smsService->getCounters();

//        $result = $smsService->getSenderName();

//        $result = $smsService->hlr($input->getArgument('phone-number'));

        var_dump($result);
    }
}
