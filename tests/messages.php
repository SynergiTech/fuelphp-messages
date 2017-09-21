<?php

namespace Test;

class Messages extends \PHPUnit\Framework\TestCase
{
    public function teardown()
    {
        \SynergiTech\Messages::resetAll();
        \Session::reset();
    }

    /**
     *
     * @covers \SynergiTech\Messages::__construct
     */
    public function test_constructor()
    {
        $this->expectException(\Error::class);
        new \SynergiTech\Messages();
    }

    public function test_instanceReturnsSingular()
    {
        $this->assertEquals(\SynergiTech\Messages::instance(), \SynergiTech\Messages::instance());
    }

    public function test_instanceReturnsNamed()
    {
        $this->assertNotEquals(\SynergiTech\Messages::instance(), \SynergiTech\Messages::instance('different'));
    }

    public function test_addError()
    {
        \SynergiTech\Messages::error("Title", "text");

        $errors = \SynergiTech\Messages::get('error');
        $this->assertCount(1, $errors);

        $error = reset($errors);
        $this->assertEquals("Title", $error['title']);
        $this->assertEquals("text", $error['text']);
    }

    public function test_addInfo()
    {
        \SynergiTech\Messages::info("Title", "text");

        $infos = \SynergiTech\Messages::get('info');
        $this->assertCount(1, $infos);

        $info = reset($infos);
        $this->assertEquals("Title", $info['title']);
        $this->assertEquals("text", $info['text']);
    }

    public function test_addWarning()
    {
        \SynergiTech\Messages::warning("Title", "text");

        $warnings = \SynergiTech\Messages::get('warning');
        $this->assertCount(1, $warnings);

        $warning = reset($warnings);
        $this->assertEquals("Title", $warning['title']);
        $this->assertEquals("text", $warning['text']);
    }

    public function test_addSuccess()
    {
        \SynergiTech\Messages::success("Title", "text");

        $successes = \SynergiTech\Messages::get('success');
        $this->assertCount(1, $successes);

        $success = reset($successes);
        $this->assertEquals("Title", $success['title']);
        $this->assertEquals("text", $success['text']);
    }

    public function test_reset()
    {
        \SynergiTech\Messages::success("Title", "text");
        $this->assertNotEmpty(\SynergiTech\Messages::get('success'));

        \SynergiTech\Messages::reset();
        $this->assertEmpty(\SynergiTech\Messages::get('success'));
    }

    public function test_resetAll()
    {
        $instance_1 = \SynergiTech\Messages::instance();
        $instance_2 = \SynergiTech\Messages::instance();

        $instance_1->error("test");
        $instance_2->info("test");
        $this->assertNotEmpty($instance_1->get());
        $this->assertNotEmpty($instance_2->get());

        \SynergiTech\Messages::resetAll();
        $this->assertEmpty($instance_1->get());
        $this->assertEmpty($instance_2->get());
    }

    public function test_keep()
    {
        \Session::set_flash('default', ['type' => 'success', 'title' => '', 'text' => '']);

        $instance = \SynergiTech\Messages::instance('default');
        $this->assertEmpty(\Session::get_flash('default', []));

        \SynergiTech\Messages::reset();
        $this->assertEmpty(\SynergiTech\Messages::get());

        \Session::set_flash('default', ['type' => 'success', 'title' => '', 'text' => '']);
        \SynergiTech\Messages::keep();
        $this->assertNotEmpty(\SynergiTech\Messages::get());
    }

    public function test_any()
    {
        $this->assertFalse(\SynergiTech\Messages::any());

        \SynergiTech\Messages::success("Title", "text");
        $this->assertTrue(\SynergiTech\Messages::any());

        \SynergiTech\Messages::reset();
        $this->assertFalse(\SynergiTech\Messages::any());
    }

    public function test_redirect()
    {
        $self = $this;
        \Response::$mock = function ($url, $location, $code) use ($self) {
            $self->assertEquals('http://google.com', $url);
            $self->assertEquals('location', $location);
            $self->assertEquals(304, $code);
        };

        \SynergiTech\Messages::redirect('http://google.com', 'location', 304);

        \Response::$mock = null;
    }

    public function test_redirectKeepsMessages()
    {
        \Response::$mock = function () {
        };

        $instance = $this->getMockBuilder(\SynergiTech\Messages\Instance::class)
            ->setConstructorArgs(['default'])
            ->setMethods(['keep'])
            ->getMock();
        $instance->expects($this->once())->method('keep');

        $instance->redirect('http://google.com', 'location', 304);

        \Response::$mock = null;
    }
}
