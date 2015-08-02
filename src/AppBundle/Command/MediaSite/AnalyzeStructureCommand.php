<?php

namespace AppBundle\Command\MediaSite;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class AnalyzeStructureCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('media:site:analyze:structure');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $startTime = microtime(true);

        $this->createTableStructure();

        $this->processing();

        $duration = microtime(true) - $startTime;
        $memory = memory_get_usage(true);
        $peak = memory_get_peak_usage(true);

        $output->writeln([
            "<info>Execute:  $duration</info>",
            "<info>Memory:   $memory B</info>",
            "<info>Peak:     $peak B</info>",
        ]);
    }

    private function processing()
    {
        $source = $this->getAllSourceTag();

        $map = [];

        $tagMap = [];

        foreach ($source as $row) {
            $tags = json_decode($row['tags'], true);

            foreach ($tags as $tag) {
                $tagMap[$tag['name']][] = $tag['list'];

                $map[$row['path']][$tag['name']] = $tag['list'];
            }
        }

        $tagClassList = array_keys($tagMap);

        $tagNameIdMap = array_flip($this->addTagClassList($tagClassList));

        $classIdTagNameIdMap = $this->addTagList($tagNameIdMap, $tagMap);

        $this->addPageToTagLinkList($map, $tagNameIdMap, $classIdTagNameIdMap);
    }

    private function getAllSourceTag()
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        return $connection->fetchAll("
            SELECT `path`, `tags` FROM `media_site_structure`
        ");
    }

    private function createTableStructure()
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        $connection->exec("DROP TABLE IF EXISTS `media_site_tag_class`;");
        $connection->exec("DROP TABLE IF EXISTS `media_site_tag`;");
        $connection->exec("DROP TABLE IF EXISTS `media_site_tag_link`;");

        $connection->exec("
            CREATE TABLE IF NOT EXISTS `media_site_tag_class`(
                `id` TINYINT PRIMARY KEY,
                `name` VARCHAR(255),
                UNIQUE KEY (`name`)
            );
        ");

        $connection->exec("
            CREATE TABLE IF NOT EXISTS `media_site_tag`(
                `id` INT PRIMARY KEY AUTO_INCREMENT,
                `class_id` TINYINT,
                `name` VARCHAR(255)
            );
        ");

        $connection->exec("
            CREATE TABLE IF NOT EXISTS `media_site_tag_link`(
                `path` VARCHAR(255),
                `tag_class_id` TINYINT,
                `tag_id` INT
            );
        ");
    }

    private function addTagClassList(array $list)
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        $result = array_combine(range(1, count($list)), $list);

        foreach ($result as $id => $tagClass) {

            $connection
                ->insert(
                    'media_site_tag_class',
                    [
                        'id' => $id,
                        'name' => $tagClass
                    ]
                );
        }

        return $result;
    }

    private function addTagList(array $classTagNameIdMap, array $classTagNameMap)
    {
        $result = [];

        $increment = 1;

        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        $connection->beginTransaction();
        foreach ($classTagNameMap as $tagClassName => $tagSourceList) {
            $classId = $classTagNameIdMap[$tagClassName];

            $tagList = array_unique(call_user_func_array('array_merge', $tagSourceList));

            foreach ($tagList as $tagName) {
                $connection->insert(
                    'media_site_tag',
                    [
                        'id' => $id = $increment++,
                        'class_id' => $classId,
                        'name' => $tagName,
                    ]
                );

                $result[$classId][$tagName] = $id;
            }
        }
        $connection->commit();

        return $result;
    }

    private function addPageList()
    {

    }

    private function addPageToTagLinkList(array $map, array $tagNameIdMap, array $classIdTagNameIdMap)
    {
        /* @var $connection Connection */
        $connection = $this->getContainer()->get('doctrine')->getConnection();

        foreach ($map as $path => $tagClassNameMap) {
            foreach ($tagClassNameMap as $tagClassName => $tagList) {
                $tagClassId = $tagNameIdMap[$tagClassName];

                foreach ($tagList as $tagName) {
                    $connection->insert('media_site_tag_link', [
                        'path' => $path,
                        'tag_class_id' => $tagClassId,
                        'tag_id' => $classIdTagNameIdMap[$tagClassId][$tagName]
                    ]);
                }
            }
        }
    }
}