<?php


namespace Codediesel\Library\QRGenerator;

class QRBitBuffer {
    private $buffer = [];
    private $length = 0;

    public function getBuffer() {
        return $this->buffer;
    }

    public function getLengthInBits() {
        return $this->length;
    }

    public function put($num, $length) {
        for ($i = 0; $i < $length; $i++) {
            $this->putBit((($num >> ($length - $i - 1)) & 1) == 1);
        }
    }

    public function putBit($bit) {

        $bufIndex = floor($this->length / 8);
        if (count($this->buffer) <= $bufIndex) {
            $this->buffer[] = 0;
        }

        if ($bit) {
            $this->buffer[$bufIndex] |= (0x80 >> ($this->length % 8));
        }

        $this->length++;
    }
}
