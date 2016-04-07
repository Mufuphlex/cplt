<?php

namespace Mufuphlex\Tests\Cplt;

use Mufuphlex\Cplt\Container;
use Mufuphlex\Cplt\ContainerItem;

class ContainerTest extends TestCase
{
    public function testAddToken()
    {
        $tokenWord = 'word';
        $tokenWorld = 'world';

        $container = new Container();
        $container->addToken($tokenWord);
        $container->addToken($tokenWorld);
        $data = $container->getData();

        $etalon = array(
            'w' => array(
                'o' => array(
                    'r' => array(
                        'word' => 1,
                        'l' => array(
                            'world' => 1,
                        )
                    ),
                ),
            ),
        );

        $this->assertEquals($etalon, $data);
    }

    /**
     * @dataProvider notStringDataProvider
     * @expectedException \InvalidArgumentException
     */
    public function testAddTokenThrowsException($token)
    {
        $container = new Container();
        $container->addToken($token);
    }

    public function testAddSimilarTokens()
    {
        $container = $this->makeSimilarTokensContainer();
        $data = $container->getData();

        $itemIancestors = array(
            'ii' => 1,
            'i' => array(
                'iii' => 1,
            )
        );

        $itemI = new ContainerItem();
        $itemI->setValue('i');
        $itemI->setPopularity(1);
        $itemI->setAncestors($itemIancestors);

        $etalon = array(
            'i' => $itemI,
        );

        $this->assertEquals($etalon, $data);
    }

    public function testFind()
    {
        $tokens = array(
            'the',
            'there',
        );

        $container = new Container();

        foreach ($tokens as $token) {
            $container->addToken($token);
        }

        $term = 'the';

        $result = $container->find($term);

        $this->assertEquals(
            array(
                'the',
                'there',
            ),
            $result
        );
    }

    public function testFindSimilarTokens()
    {
        $container = $this->makeSimilarTokensContainer();
        $result = $container->find('i');

        $this->assertEquals(
            array(
                'i',
                'iii',
                'ii',
            ),
            $result
        );
    }

    public function testFindCorrectTokens()
    {
        $tokens = array(
            "the",
            "to",
            "that",
            "them",
            "took",
            "this",
            "they",
            "tea",
            "take",
            "time",
            "turned",
            "there",
            "thought",
            "t",
            "then",
            "table",
            "through",
            "thou",
            "tears",
            "these",
            "too",
            "tried",
            "thee",
            "thy",
            "thinking",
            "things",
            "thing",
            "told",
            "tear",
            "testament",
            "tell",
            "trouble",
            "taketh",
            "taken",
            "talk",
            "trying",
            "tired",
            "thanked",
            "thanks",
            "those",
            "twenty",
            "their",
            "think",
            "two",
            "than",
            "till",
            "thus",
            "turn",
            "teeth",
            "times",
            "tools",
            "tsa",
            "tis",
            "teach",
            "tidings",
            "twice",
            "torn",
            "treated",
            "threatening",
            "threaten",
            "threw",
            "three",
            "tro",
            "thoughtless",
            "thoughts",
            "thine",
            "third",
            "thirsty",
            "thin",
            "trinity",
            "trick",
            "taught",
            "talking",
            "task",
            "tapped",
            "toward",
            "talked",
            "takes",
            "traktir",
            "treat",
            "treating",
            "trunk",
            "towel",
        );

        $container = new Container();

        foreach ($tokens as $token) {
            $container->addToken($token);
        }

        $result = $container->find('thirs');
        $this->assertEquals(array('thirsty'), $result);

        $result = $container->find('the');
        $this->assertFalse(in_array('than', $result));
    }

    public function testNonScalar()
    {
        $tokens = array(
            'i',
            'i',
            'if',
            'if',
            'infamies',
            'i',
            'is',
            'i',
            'i',
            'i',
            'it',
            'in',
            'importance',
            'in',
            'invitations',
            'in',
            'if',
            'if',
            'invalid',
            'is',
            'i',
            'in',
            'in',
            'in',
            'intonation',
            'importance',
            'in',
            'indifference',
            'irony',
            'in',
            'if',
            'i',
            'is',
            'i',
            'in',
            'is',
            'i',
            'i',
            'if',
            'it',
            'it',
            'in',
            'i',
            'impulsiveness',
            'it',
            'in',
            'it',
            'in',
            'it',
            'in',
            'i',
            'is',
            'it',
            'is',
            'i',
            'in',
            'is',
            'in',
            'i',
            'in',
            'is',
            'invincible',
            'is',
            'i',
            'is',
            'i',
            'in',
            'impetuosity',
            'i',
            'if',
            'instead',
            'in',
            'i',
            'interesting',
            'is',
            'is',
            'i',
            'if',
            'it',
            'is',
            'it',
            'is',
            'it',
            'indicate',
            'in',
            'illustrious',
            'indifferent',
            'is',
            'i',
            'if',
            'intimate',
            'i',
            'i',
            'i',
            'in',
            'i',
            'it',
            'i',
            'i',
            'i',
            'its',
            'i',
            'is',
            'is',
            'is',
            'in',
            'if',
            'i',
            'i',
            'i',
            'it',
            'is',
            'i',
            'is',
            'i',
            'it',
            'it',
            'i',
            'in',
            'i',
            'is',
            'is',
            'indicated',
            'information',
            'is',
            'it',
            'in',
            'if',
            'is',
            'is',
            'in',
            'is',
            'is',
            'is',
            'i',
            'is',
            'it',
            'i',
            'in',
            'is',
            'i',
            'it',
            'it',
            'in',
            'in',
            'i',
            'it',
            'i',
            'ii',
            'in',
            'in',
            'introduced',
            'in',
            'in',
            'in',
            'interest',
            'in',
            'impatience',
            'in',
            'it',
            'it',
            'is',
            'in',
            'if',
            'in',
            'if',
            'i',
            'in',
            'i',
            'it',
            'i',
            'in',
            'in',
            'is',
            'is',
            'is',
            'is',
            'illegitimate',
            'in',
            'in',
            'in',
            'in',
            'in',
            'in',
            'it',
            'is',
            'invalid',
            'if',
            'in',
            'intimate',
            'in',
            'is',
            'interesting',
            'i',
            'it',
            'is',
            'interesting',
            'in',
            'impoliteness',
            'it',
            'it',
            'it',
            'in',
            'in',
            'in',
            'intellectual',
            'in',
            'interesting',
            'iii',
        );

        $container = new Container();

        foreach ($tokens as $token) {
            $container->addToken($token);
        }
    }

    private function makeSimilarTokensContainer()
    {
        $tokens = array(
            'i',
            'ii',
            'iii',
        );

        $container = new Container();

        foreach ($tokens as $token) {
            $container->addToken($token);
        }

        return $container;
    }
}