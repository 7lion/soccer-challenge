<?php

namespace AppBundle\Command;

use AppBundle\Service\Crawler\CrawlerProcessResponse;
use AppBundle\Service\Crawler\UrlBuilder;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SoccerFetchDataCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('soccer:fetch-data')
            ->setDescription('This command helps to fetch data from the site');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getContainer();
        $processManager = $container->get('crawler.process.manager');
        $logger = $container->get('logger');

        $page = UrlBuilder::DEFAULT_PAGE;
        $countOfCrawledPages = 1;
        while ($response = $processManager->process($page)) {
            $output->write(
                sprintf('<info>Crawl page %d: </info>', $countOfCrawledPages)
            );

            switch ($response->getStatus()) {
                case CrawlerProcessResponse::STATUS_HAS_NEXT:
                    $page = $response->getNextPage();
                    $output->writeln(
                        sprintf('<info>%s</info>', $response->getMessage())
                    );

                    sleep($response->getWaitingInSeconds());
                    break;
                case CrawlerProcessResponse::STATUS_ERROR:
                    $logger->error('Command soccer:fetch-data', ['message' => $response->getMessage()]);
                    $output->writeln(
                        sprintf('<error>Some error - %s</error>', $response->getMessage())
                    );
                    die;
                case CrawlerProcessResponse::STATUS_FINISHED:
                    $output->writeln(
                        sprintf('<info>%s</info>', $response->getMessage())
                    );
                    $output->writeln("\r\n");
                    $output->writeln('<info>Crawler process finished. </info>');
                    $output->writeln(
                        sprintf('<info>Count of crawled pages - %d</info>', $countOfCrawledPages)
                    );
                    die;
            }
            $countOfCrawledPages++;
        }
    }
}
