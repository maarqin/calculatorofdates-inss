<?php

namespace src;


use DateInterval;
use DateTime;

class Interval {

    private $msg;
    private $init;
    private $end;
    private $adicional;

    public function getDiff() {
        $init = \DateTime::createFromFormat("Y-m-d", $this->init);
        $end = \DateTime::createFromFormat("Y-m-d", $this->end);

        $end->modify("+1 day");

        if( $init < $end ) {
            return $init->diff($end, true);
        }
        throw new \InvalidArgumentException("Data inicial deve ser inferior à final");
    }

    /**
     * @param mixed $init
     */
    public function setInit($init)
    {
        $this->init = $init;
    }

    /**
     * @param mixed $end
     */
    public function setEnd($end)
    {
        $this->end = $end;
    }

    /**
     * @return mixed
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     * @param mixed $msg
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
    }

    /**
     * @return mixed
     */
    public function getInit()
    {
        return $this->init;
    }

    /**
     * @return mixed
     */
    public function getEnd()
    {
        return $this->end;
    }

    /**
     * @return mixed
     */
    public function getAdicional()
    {
        return $this->adicional;
    }

    /**
     * @param mixed $adicional
     */
    public function setAdicional($adicional)
    {
        $this->adicional = $adicional;
    }

    /**
     * @return mixed
     */
    public function getInitFormated()
    {
        return \DateTime::createFromFormat("Y-m-d", $this->init)->format('d/m/Y');
    }

    /**
     * @return mixed
     */
    public function getEndFormated()
    {
        return \DateTime::createFromFormat("Y-m-d", $this->end)->format('d/m/Y');
    }

    public function getMilliseconds()
    {
        return strtotime($this->init);
    }

    public function compare(Interval $init, Interval $end)
    {

        if( $init->getMilliseconds() < $end->getMilliseconds() ) {
            return -1;
        } else if( $init->getMilliseconds() > $end->getMilliseconds() ) {
            return 1;
        }
        return 0;
    }

}