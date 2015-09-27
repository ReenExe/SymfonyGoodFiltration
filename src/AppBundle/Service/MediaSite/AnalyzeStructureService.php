<?php

namespace AppBundle\Service\MediaSite;

use AppBundle\Service\AbstractQueueService;

class AnalyzeStructureService extends AbstractQueueService
{
    protected function process($limit)
    {
        $source = $this->getAllSourceTag();

        $map = [];

        $tagMap = [];

        foreach ($source as $row) {
            $tags = json_decode($row['tags'], true);

            foreach ($tags as $tag) {
                $tagMap[$tag['name']][] = $tag['list'];

                $map[$row['id']][$tag['name']] = $tag['list'];
            }
        }

        $tagClassList = array_keys($tagMap);

        $tagNameIdMap = array_flip($this->addTagClassList($tagClassList));

        $classIdTagNameIdMap = $this->addTagList($tagNameIdMap, $tagMap);

        $this->addPageToTagLinkList($map, $tagNameIdMap, $classIdTagNameIdMap);
    }

    private function getAllSourceTag()
    {
        return $this->connection->fetchAll("
            SELECT `id`, `tags` FROM `media_site_structure`
        ");
    }

    protected function createCache()
    {
        $this->createTableStructure();
    }

    private function createTableStructure()
    {
        $this->connection->exec("DROP TABLE IF EXISTS `media_site_tag_class`;");
        $this->connection->exec("DROP TABLE IF EXISTS `media_site_tag`;");
        $this->connection->exec("DROP TABLE IF EXISTS `media_site_tag_link`;");

        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `media_site_tag_class`(
                `id` TINYINT PRIMARY KEY,
                `name` VARCHAR(255),
                UNIQUE KEY (`name`)
            );
        ");

        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `media_site_tag`(
                `id` INT PRIMARY KEY AUTO_INCREMENT,
                `class_id` TINYINT,
                `name` VARCHAR(255)
            );
        ");

        $this->connection->exec("
            CREATE TABLE IF NOT EXISTS `media_site_tag_link`(
                `id` VARCHAR(255),
                `tag_class_id` TINYINT,
                `tag_id` INT
            );
        ");
    }

    private function addTagClassList(array $list)
    {
        $result = array_combine(range(1, count($list)), $list);

        foreach ($result as $id => $tagClass) {

            $this->connection
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

        $this->connection->beginTransaction();
        foreach ($classTagNameMap as $tagClassName => $tagSourceList) {
            $classId = $classTagNameIdMap[$tagClassName];

            $tagList = array_unique(call_user_func_array('array_merge', $tagSourceList));

            foreach ($tagList as $tagName) {
                $this->connection->insert(
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
        $this->connection->commit();

        return $result;
    }

    private function addPageToTagLinkList(array $map, array $tagNameIdMap, array $classIdTagNameIdMap)
    {
        foreach (array_chunk($map, 5000, true) as $mapChunk) {

            $this->connection->beginTransaction();

            foreach ($mapChunk as $id => $tagClassNameMap) {
                foreach ($tagClassNameMap as $tagClassName => $tagList) {
                    $tagClassId = $tagNameIdMap[$tagClassName];
                    foreach ($tagList as $tagName) {
                        $this->connection->insert('media_site_tag_link', [
                            'id' => $id,
                            'tag_class_id' => $tagClassId,
                            'tag_id' => $classIdTagNameIdMap[$tagClassId][$tagName]
                        ]);
                    }
                }
            }

            $this->connection->commit();
        }
    }
}