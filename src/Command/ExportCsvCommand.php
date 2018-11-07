<?php
// src/Command/ExportCsvCommand
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ExportCsvCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        // the name of the command (the part after "bin/console")
        ->setName('app:export-product-csv')

        // the short description shown while running "php bin/console list"
        ->setDescription('Export all product in CSV.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp('This command allows you to export all products in CSV...');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$em = $this->getContainer()->get('doctrine')->getEntityManager();
        $products = $em->getRepository(Product::class)->findAll();

        $fp = fopen('export_products.csv', 'w');
        fputcsv($fp, array('id','name','description','price'));
		foreach ($products as $product) {
		    fputcsv($fp, array($product->getId(),$product->getName(),$product->getDescription(),$product->getPrice()));
		}

		fclose($fp);

        $output->writeln('Your csv export file is available : export_products.csv');
    }
}