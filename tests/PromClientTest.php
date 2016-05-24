<?php

namespace Aptarus\PromClientTest;

use Aptarus\PromClient;

class PromClientTest extends \PHPUnit_Framework_TestCase
{
    public static $PromClient_delete = true;

    public static function setUpBeforeClass()
    {
        file_exists('data/metrics.db') and unlink('data/metrics.db');
        if (!is_dir('data'))
        {
            mkdir('data');
        }
        PromClient\Configuration::$storage_dir = 'data';
    }

    public function testCounter()
    {
        $c = PromClient\Counter('test_counter');
        $this->assertEquals("Aptarus\\PromClient\\Counter", get_class($c));
        $c->inc();
        // TODO: read counter and check that it's incremented.
    }

    public function testCounterLabel()
    {
        $c = PromClient\Counter('test_counter', 'Test counter',
                                array('l1', 'l2'));
        $this->assertEquals("Aptarus\\PromClient\\_LabelWrapper",
            get_class($c));
        $cl = $c->labels(array(1, 2));
        $this->assertEquals("Aptarus\\PromClient\\Counter", get_class($cl));
        return $cl;
    }

    /**
     * @depends testCounterLabel
     */
    public function testCounterLabelInc($c)
    {
        $c->inc();
        // TODO: read counter and check that it's incremented.
    }

    public function testGauge()
    {
        $g = PromClient\Gauge('test_gauge');
        $this->assertEquals("Aptarus\\PromClient\\Gauge", get_class($g));
        $g->inc();
        // TODO: read counter and check that it's incremented.
        $g->dec();
        // TODO: read counter and check that it's incremented.
        $g->set(100);
        // TODO: read counter and check that it's incremented.
        $g->set_to_current_time();
        // TODO: read counter and check that it's incremented.
    }

    public function testGaugeLabel()
    {
        $g = PromClient\Gauge('test_gauge', 'Test gauge',
                                array('l1', 'l2'));
        $this->assertEquals("Aptarus\\PromClient\\_LabelWrapper",
            get_class($g));
        $gl = $g->labels(array(1, 2));
        $this->assertEquals("Aptarus\\PromClient\\Gauge", get_class($gl));
        return $gl;
    }

    /**
     * @depends testGaugeLabel
     */
    public function testGaugeLabelInc($g)
    {
        $g->inc();
        // TODO: read gauge and check that it's incremented.
    }

    protected function onNotSuccessfulTest(\Exception $e)
    {
        self::$PromClient_delete = false;
        fwrite(STDOUT, 'Not deleting data/metrics.db due to test failure.');
        throw $e;
    }

    public static function tearDownAfterClass()
    {
        if (self::$PromClient_delete)
        {
            file_exists('data/metrics.db') and unlink('data/metrics.db');
        }
    }
}

// vim:sw=4 ts=4 et