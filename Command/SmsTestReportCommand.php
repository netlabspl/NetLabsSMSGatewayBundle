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
class SmsTestReportCommand extends ContainerAwareCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setName('sms:test:report')
            ->setDescription('Checks SMS messages report')
            ->addOption('number', null, InputOption::VALUE_NONE, 'Phone number')
            ->addOption('startDate', null, InputOption::VALUE_NONE, 'Start date (must be provided with endDate)')
            ->addOption('endDate', null, InputOption::VALUE_NONE, 'End date (must be provided with startDate)')
            ->addOption('smsId', null, InputOption::VALUE_NONE, 'SMS ID')
            ->addOption('identifier', null, InputOption::VALUE_NONE, 'Message custom identifier');
    }

    /**
     * @see Command
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $smsService = $this->getContainer()->get('net_labs_sms');
        $smsService->setIsTest(true);

        $result = $smsService->getReports(
            $input->getOption('number'),
            $input->getOption('startDate'),
            $input->getOption('endDate'),
            $input->getOption('smsId'),
            $input->getOption('identifier')
        );

        var_dump($result);
    }
}
