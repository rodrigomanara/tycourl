<?php

namespace Codediesel\Library\QRGenerator;


class QRPolynomial {
    private $num;

    public function __construct($num, $shift) {
        if ($num == null) throw new \Exception("num is null");

        $offset = 0;
        while ($offset < count($num) && $num[$offset] == 0) {
            $offset++;
        }

        $this->num = array_fill(0, count($num) - $offset + $shift, 0);
        for ($i = 0; $i < count($num) - $offset; $i++) {
            $this->num[$i] = $num[$i + $offset];
        }
    }

    public function getNum() {
        return $this->num;
    }

    public function multiply($e) {
        $num = array_fill(0, count($this->num) + count($e->num) - 1, 0);

        for ($i = 0; $i < count($this->num); $i++) {
            for ($j = 0; $j < count($e->num); $j++) {
                $num[$i + $j] ^= QRMath::gexp(QRMath::glog($this->num[$i]) + QRMath::glog($e->num[$j]));
            }
        }

        return new QRPolynomial($num, 0);
    }

    public function mod($e) {
        if (count($this->num) - count($e->num) < 0) {
            return $this;
        }

        $ratio = QRMath::glog($this->num[0]) - QRMath::glog($e->num[0]);
        $num = [];

        for ($i = 0; $i < count($this->num); $i++) {
            $num[$i] = $this->num[$i];
        }

        for ($i = 0; $i < count($e->num); $i++) {
            $num[$i] ^= QRMath::gexp(QRMath::glog($e->num[$i]) + $ratio);
        }

        return (new QRPolynomial($num, 0))->mod($e);
    }
}

