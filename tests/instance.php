<?php

namespace Test;

class Instance extends \PHPUnit\Framework\TestCase
{
    public function teardown()
    {
        \SynergiTech\Messages::resetAll();
        \Session::reset();
    }

    public function test_addMessageValidationError()
    {
        $instance = new \SynergiTech\Messages\Instance('default');
        $error = new \Validation_Error('validation error');
        $instance->error($error);

        $errors = $instance->get('error');
        $this->assertNotEmpty($errors);

        $error = reset($errors);
        $this->assertEquals($error['title'], 'validation error');
    }

    public function test_getName()
    {
        $instance = new \SynergiTech\Messages\Instance('default');
        $this->assertEquals('default', $instance->getName());

        $instance = new \SynergiTech\Messages\Instance('test');
        $this->assertEquals('test', $instance->getName());
    }

    public function test_shutdown()
    {
        $instance = new \SynergiTech\Messages\Instance('default');
        
        $instance->info("test");
        $this->assertEmpty(\Session::get_flash('default', []));

        $instance->shutdown();
        $this->assertNotEmpty(\Session::get_flash('default', []));
    }

    public function test_arrayAccess()
    {
        $instance = new \SynergiTech\Messages\Instance('default');

        for ($i = 0; $i < 5; $i++) {
            $instance->info($i);
        }
        for ($i = 0; $i < 10; $i++) {
            $instance->success($i);
        }

        $this->assertArrayHasKey(0, $instance);
        $this->assertArrayHasKey(14, $instance);

        unset($instance[0]);
        $this->assertArrayNotHasKey(0, $instance);

        $instance->reset();
        $instance[] = ['type' => 'info', 'title' => 'test', 'text' => ''];
        $instance['test'] = ['type' => 'info', 'title' => 'test', 'text' => ''];
        $this->assertCount(2, $instance->get('info'));

        $msg = $instance[0];
        $this->assertEquals('test', $msg['title']);
    }

    public function test_iterator()
    {
        $instance = new \SynergiTech\Messages\Instance('default');

        for ($i = 0; $i < 5; $i++) {
            $instance->info($i);
        }
        for ($i = 0; $i < 10; $i++) {
            $instance->success($i);
        }

        $c = 0;
        foreach ($instance as $key => $msg) {
            $c++;
        }
        $this->assertEquals(15, $c);
    }
}
