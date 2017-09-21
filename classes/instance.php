<?php

namespace SynergiTech\Messages;

class Instance implements \ArrayAccess, \Iterator
{
    protected $messages = array();

    protected $instance = null;

    public function __construct($name)
    {
        $this->instance = $name;
        $this->messages = \Session::get_flash($this->instance, array());

        \Event::register('shutdown', array($this, 'shutdown'), true);
    }

    public function getName()
    {
        return $this->instance;
    }

    public function shutdown()
    {
        \Session::set_flash($this->instance, $this->messages);
    }

    public function error($title, $text = null)
    {
        return $this->add_message('error', $title, $text);
    }

    public function info($title, $text = null)
    {
        return $this->add_message('info', $title, $text);
    }

    public function warning($title, $text = null)
    {
        return $this->add_message('warning', $title, $text);
    }

    public function success($title, $text = null)
    {
        return $this->add_message('success', $title, $text);
    }

    public function reset()
    {
        $this->messages = array();

        return $this;
    }

    public function keep()
    {
        $this->messages = array_merge(\Session::get_flash($this->instance, array()), $this->messages);

        return $this;
    }

    public function any()
    {
        return count($this->messages) > 0;
    }

    public function get($type = null)
    {
        $messages = array();

        foreach ($this->messages as $message) {
            if ($type === null || $message['type'] == $type) {
                $messages[] = $message;
            }
        }

        return $messages;
    }

    public function redirect($url = '', $method = 'location', $code = 302)
    {
        $this->keep();

        \Response::redirect($url, $method, $code);
    }

    public function addMessage($type, $titles, $text = null)
    {
        is_array($titles) || $titles = array($titles);

        foreach ($titles as $title) {
            if ($title instanceof \Validation_Error) {
                $title = $title->get_message();
            }

            array_push($this->messages, array(
                'type' => $type,
                'title' => $title,
                'text' => $text
            ));
        }

        return $this;
    }

    /**
     * @deprecated
     */
    // @codingStandardsIgnoreLine
    public function add_message($type, $titles, $text = null)
    {
        return $this->addMessage($type, $titles, $text);
    }

    public function rewind()
    {
        reset($this->messages);
    }

    public function current()
    {
        return current($this->messages);
    }

    public function key()
    {
        return key($this->messages);
    }

    public function next()
    {
        return next($this->messages);
    }

    public function valid()
    {
        return key($this->messages) !== null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->messages[] = $value;
            return;
        }
        $this->messages[$offset] = $value;
    }

    public function offsetExists($offset)
    {
        return isset($this->messages[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->messages[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->messages[$offset]) ? $this->messages[$offset] : null;
    }
}
