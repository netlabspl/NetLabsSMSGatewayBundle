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
class SmsTestHLRCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('sms:test:hlr')
            ->setDescription('Checks networks of phone number (HLR)')
            ->addArgument('phone-number', InputArgument::REQUIRED, "Phone number");
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $smsService = $this->getContainer()->get('net_labs_sms');
        $smsService->setIsTest(true);

        $result = $smsService->hlr($input->getArgument('phone-number'));

        var_dump($result);
    }
}
