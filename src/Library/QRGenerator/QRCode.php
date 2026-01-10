<?php

namespace Codediesel\Library\QRGenerator;


/**
 * PHP QR Code Generator - Working Version
 * This includes a lightweight QR code encoder implementation
 * Save this as: qr_generator.php
 * Requires: PHP GD extension
 */
// Simple but working QR Co

class QRCode {
    private $data;
    private $eccLevel;
    private $size;
    private $version = 1;
    private $moduleCount = 21;
    private $modules = [];
    private $isFunction = [];

    const MODE_NUMBER = 1;
    const MODE_ALPHA_NUM = 2;
    const MODE_8BIT_BYTE = 4;

    const ERROR_CORRECT_L = 1;
    const ERROR_CORRECT_M = 0;
    const ERROR_CORRECT_Q = 3;
    const ERROR_CORRECT_H = 2;

    public function __construct($data, $errorCorrectLevel = self::ERROR_CORRECT_M) {
        $this->data = $data;
        $this->eccLevel = $errorCorrectLevel;
        $this->version = $this->getMinimumVersion($data, $errorCorrectLevel);
        $this->makeImpl();
    }

    private function getMinimumVersion($data, $errorCorrectLevel) {
        $length = strlen($data);

        // Capacity table for 8-bit byte mode
        $capacities = [
            self::ERROR_CORRECT_L => [17, 32, 53, 78, 106, 134, 154, 192, 230, 271],
            self::ERROR_CORRECT_M => [14, 26, 42, 62, 84, 106, 122, 152, 180, 213],
            self::ERROR_CORRECT_Q => [11, 20, 32, 46, 60, 74, 86, 108, 130, 151],
            self::ERROR_CORRECT_H => [7, 14, 24, 34, 44, 58, 64, 84, 98, 119]
        ];

        for ($v = 1; $v <= 10; $v++) {
            if ($length <= $capacities[$errorCorrectLevel][$v - 1]) {
                return $v;
            }
        }

        return 10; // Maximum version we support
    }

    private function makeImpl() {
        $this->moduleCount = $this->version * 4 + 17;
        $this->modules = array_fill(0, $this->moduleCount, array_fill(0, $this->moduleCount, null));
        $this->isFunction = array_fill(0, $this->moduleCount, array_fill(0, $this->moduleCount, false));

        $this->setupPositionProbePattern(0, 0);
        $this->setupPositionProbePattern($this->moduleCount - 7, 0);
        $this->setupPositionProbePattern(0, $this->moduleCount - 7);
        $this->setupPositionAdjustPattern();
        $this->setupTimingPattern();
        $this->setupTypeInfo(true, 0);

        if ($this->version >= 7) {
            $this->setupTypeNumber(true);
        }

        $data = $this->createData($this->version, $this->eccLevel, $this->data);
        $this->mapData($data, 0);
    }

    private function setupPositionProbePattern($row, $col) {
        for ($r = -1; $r <= 7; $r++) {
            if ($row + $r <= -1 || $this->moduleCount <= $row + $r) continue;

            for ($c = -1; $c <= 7; $c++) {
                if ($col + $c <= -1 || $this->moduleCount <= $col + $c) continue;

                if ((0 <= $r && $r <= 6 && ($c == 0 || $c == 6))
                    || (0 <= $c && $c <= 6 && ($r == 0 || $r == 6))
                    || (2 <= $r && $r <= 4 && 2 <= $c && $c <= 4)) {
                    $this->modules[$row + $r][$col + $c] = true;
                } else {
                    $this->modules[$row + $r][$col + $c] = false;
                }
                $this->isFunction[$row + $r][$col + $c] = true;
            }
        }
    }

    private function setupPositionAdjustPattern() {
        $pos = $this->getAlignmentPattern($this->version);

        if (empty($pos)) return;

        for ($i = 0; $i < count($pos); $i++) {
            for ($j = 0; $j < count($pos); $j++) {
                $row = $pos[$i];
                $col = $pos[$j];

                if ($this->modules[$row][$col] !== null) continue;

                for ($r = -2; $r <= 2; $r++) {
                    for ($c = -2; $c <= 2; $c++) {
                        if ($r == -2 || $r == 2 || $c == -2 || $c == 2 || ($r == 0 && $c == 0)) {
                            $this->modules[$row + $r][$col + $c] = true;
                        } else {
                            $this->modules[$row + $r][$col + $c] = false;
                        }
                        $this->isFunction[$row + $r][$col + $c] = true;
                    }
                }
            }
        }
    }

    private function getAlignmentPattern($version) {
        $patterns = [
            [], // Version 1
            [6, 18], // Version 2
            [6, 22], // Version 3
            [6, 26], // Version 4
            [6, 30], // Version 5
            [6, 34], // Version 6
            [6, 22, 38], // Version 7
            [6, 24, 42], // Version 8
            [6, 26, 46], // Version 9
            [6, 28, 50]  // Version 10
        ];

        return $patterns[$version - 1] ?? [];
    }

    private function setupTimingPattern() {
        for ($r = 8; $r < $this->moduleCount - 8; $r++) {
            if ($this->modules[$r][6] !== null) continue;
            $this->modules[$r][6] = ($r % 2 == 0);
            $this->isFunction[$r][6] = true;
        }

        for ($c = 8; $c < $this->moduleCount - 8; $c++) {
            if ($this->modules[6][$c] !== null) continue;
            $this->modules[6][$c] = ($c % 2 == 0);
            $this->isFunction[6][$c] = true;
        }
    }

    private function setupTypeInfo($test, $maskPattern) {
        $data = ($this->eccLevel << 3) | $maskPattern;
        $bits = $this->getBCHTypeInfo($data);

        for ($i = 0; $i < 15; $i++) {
            $mod = (!$test && (($bits >> $i) & 1) == 1);

            if ($i < 6) {
                $this->modules[$i][8] = $mod;
                $this->isFunction[$i][8] = true;
            } else if ($i < 8) {
                $this->modules[$i + 1][8] = $mod;
                $this->isFunction[$i + 1][8] = true;
            } else {
                $this->modules[$this->moduleCount - 15 + $i][8] = $mod;
                $this->isFunction[$this->moduleCount - 15 + $i][8] = true;
            }
        }

        for ($i = 0; $i < 15; $i++) {
            $mod = (!$test && (($bits >> $i) & 1) == 1);

            if ($i < 8) {
                $this->modules[8][$this->moduleCount - $i - 1] = $mod;
                $this->isFunction[8][$this->moduleCount - $i - 1] = true;
            } else if ($i < 9) {
                $this->modules[8][15 - $i - 1 + 1] = $mod;
                $this->isFunction[8][15 - $i - 1 + 1] = true;
            } else {
                $this->modules[8][15 - $i - 1] = $mod;
                $this->isFunction[8][15 - $i - 1] = true;
            }
        }

        $this->modules[$this->moduleCount - 8][8] = !$test;
        $this->isFunction[$this->moduleCount - 8][8] = true;
    }

    private function setupTypeNumber($test) {
        // Not needed for version 1
    }

    private function getBCHTypeInfo($data) {
        $d = $data << 10;
        while ($this->getBCHDigit($d) - $this->getBCHDigit(0x537) >= 0) {
            $d ^= (0x537 << ($this->getBCHDigit($d) - $this->getBCHDigit(0x537)));
        }
        return (($data << 10) | $d) ^ 0x5412;
    }

    private function getBCHDigit($data) {
        $digit = 0;
        while ($data != 0) {
            $digit++;
            $data >>= 1;
        }
        return $digit;
    }

    private function createData($version, $errorCorrectLevel, $data) {
        $rsBlocks = $this->getRSBlocks($version, $errorCorrectLevel);

        $buffer = new QRBitBuffer();
        $buffer->put(self::MODE_8BIT_BYTE, 4);

        // Get correct length bits based on version
        $lengthBits = $version < 10 ? 8 : 16;
        $buffer->put(strlen($data), $lengthBits);

        for ($i = 0; $i < strlen($data); $i++) {
            $buffer->put(ord($data[$i]), 8);
        }

        $totalDataCount = 0;
        foreach ($rsBlocks as $block) {
            $totalDataCount += $block[0];
        }

        if ($buffer->getLengthInBits() > $totalDataCount * 8) {
            throw new \Exception("Data too long for version " . $version);
        }

        if ($buffer->getLengthInBits() + 4 <= $totalDataCount * 8) {
            $buffer->put(0, 4);
        }

        while ($buffer->getLengthInBits() % 8 != 0) {
            $buffer->putBit(false);
        }

        while (true) {
            if ($buffer->getLengthInBits() >= $totalDataCount * 8) break;
            $buffer->put(0xEC, 8);

            if ($buffer->getLengthInBits() >= $totalDataCount * 8) break;
            $buffer->put(0x11, 8);
        }

        return $this->createBytes($buffer, $rsBlocks);
    }

    private function createBytes($buffer, $rsBlocks) {
        $offset = 0;
        $maxDcCount = 0;
        $maxEcCount = 0;

        $dcdata = array_fill(0, count($rsBlocks), null);
        $ecdata = array_fill(0, count($rsBlocks), null);

        foreach ($rsBlocks as $r => $block) {
            $dcCount = $block[0];
            $ecCount = $block[1];

            $maxDcCount = max($maxDcCount, $dcCount);
            $maxEcCount = max($maxEcCount, $ecCount);

            $dcdata[$r] = array_fill(0, $dcCount, 0);

            for ($i = 0; $i < count($dcdata[$r]); $i++) {
                $dcdata[$r][$i] = 0xff & $buffer->getBuffer()[$i + $offset];
            }
            $offset += $dcCount;

            $rsPoly = $this->getErrorCorrectPolynomial($ecCount);
            $rawPoly = new QRPolynomial($dcdata[$r], count($rsPoly->getNum()) - 1);

            $modPoly = $rawPoly->mod($rsPoly);
            $ecdata[$r] = array_fill(0, count($rsPoly->getNum()) - 1, 0);
            for ($i = 0; $i < count($ecdata[$r]); $i++) {
                $modIndex = $i + count($modPoly->getNum()) - count($ecdata[$r]);
                $ecdata[$r][$i] = ($modIndex >= 0) ? $modPoly->getNum()[$modIndex] : 0;
            }
        }

        $totalCodeCount = 0;
        foreach ($rsBlocks as $block) {
            $totalCodeCount += $block[0];
        }

        $data = array_fill(0, $totalCodeCount, 0);
        $index = 0;

        for ($i = 0; $i < $maxDcCount; $i++) {
            foreach ($rsBlocks as $r => $block) {
                if ($i < count($dcdata[$r])) {
                    $data[$index++] = $dcdata[$r][$i];
                }
            }
        }

        for ($i = 0; $i < $maxEcCount; $i++) {
            foreach ($rsBlocks as $r => $block) {
                if ($i < count($ecdata[$r])) {
                    $data[$index++] = $ecdata[$r][$i];
                }
            }
        }

        return $data;
    }

    private function getRSBlocks($version, $errorCorrectLevel) {
        $rsBlock = $this->getRsBlockTable($version, $errorCorrectLevel);
        $list = [];

        for ($i = 0; $i < count($rsBlock) / 3; $i++) {
            $count = $rsBlock[$i * 3 + 0];
            $totalCount = $rsBlock[$i * 3 + 1];
            $dataCount = $rsBlock[$i * 3 + 2];

            for ($j = 0; $j < $count; $j++) {
                $list[] = [$dataCount, $totalCount - $dataCount];
            }
        }

        return $list;
    }

    private function getRsBlockTable($version, $errorCorrectLevel) {
        // RS block specifications: [block count, total count, data count]
        $tables = [
            // Version 1
            1 => [
                self::ERROR_CORRECT_L => [1, 26, 19],
                self::ERROR_CORRECT_M => [1, 26, 16],
                self::ERROR_CORRECT_Q => [1, 26, 13],
                self::ERROR_CORRECT_H => [1, 26, 9]
            ],
            // Version 2
            2 => [
                self::ERROR_CORRECT_L => [1, 44, 34],
                self::ERROR_CORRECT_M => [1, 44, 28],
                self::ERROR_CORRECT_Q => [1, 44, 22],
                self::ERROR_CORRECT_H => [1, 44, 16]
            ],
            // Version 3
            3 => [
                self::ERROR_CORRECT_L => [1, 70, 55],
                self::ERROR_CORRECT_M => [1, 70, 44],
                self::ERROR_CORRECT_Q => [2, 35, 17],
                self::ERROR_CORRECT_H => [2, 35, 13]
            ],
            // Version 4
            4 => [
                self::ERROR_CORRECT_L => [1, 100, 80],
                self::ERROR_CORRECT_M => [2, 50, 32],
                self::ERROR_CORRECT_Q => [2, 50, 24],
                self::ERROR_CORRECT_H => [4, 25, 9]
            ],
            // Version 5
            5 => [
                self::ERROR_CORRECT_L => [1, 134, 108],
                self::ERROR_CORRECT_M => [2, 67, 43],
                self::ERROR_CORRECT_Q => [2, 33, 15, 2, 34, 16],
                self::ERROR_CORRECT_H => [2, 33, 11, 2, 34, 12]
            ],
            // Version 6
            6 => [
                self::ERROR_CORRECT_L => [2, 86, 68],
                self::ERROR_CORRECT_M => [4, 43, 27],
                self::ERROR_CORRECT_Q => [4, 43, 19],
                self::ERROR_CORRECT_H => [4, 43, 15]
            ],
            // Version 7
            7 => [
                self::ERROR_CORRECT_L => [2, 98, 78],
                self::ERROR_CORRECT_M => [4, 49, 31],
                self::ERROR_CORRECT_Q => [2, 32, 14, 4, 33, 15],
                self::ERROR_CORRECT_H => [4, 39, 13, 1, 40, 14]
            ],
            // Version 8
            8 => [
                self::ERROR_CORRECT_L => [2, 121, 97],
                self::ERROR_CORRECT_M => [2, 60, 38, 2, 61, 39],
                self::ERROR_CORRECT_Q => [4, 40, 18, 2, 41, 19],
                self::ERROR_CORRECT_H => [4, 40, 14, 2, 41, 15]
            ],
            // Version 9
            9 => [
                self::ERROR_CORRECT_L => [2, 146, 116],
                self::ERROR_CORRECT_M => [3, 58, 36, 2, 59, 37],
                self::ERROR_CORRECT_Q => [4, 36, 16, 4, 37, 17],
                self::ERROR_CORRECT_H => [4, 36, 12, 4, 37, 13]
            ],
            // Version 10
            10 => [
                self::ERROR_CORRECT_L => [2, 86, 68, 2, 87, 69],
                self::ERROR_CORRECT_M => [4, 69, 43, 1, 70, 44],
                self::ERROR_CORRECT_Q => [6, 43, 19, 2, 44, 20],
                self::ERROR_CORRECT_H => [6, 43, 15, 2, 44, 16]
            ]
        ];

        return $tables[$version][$errorCorrectLevel] ?? [1, 26, 16];
    }

    private function getErrorCorrectPolynomial($errorCorrectLength) {
        $a = new QRPolynomial([1], 0);

        for ($i = 0; $i < $errorCorrectLength; $i++) {
            $a = $a->multiply(new QRPolynomial([1, $this->gexp($i)], 0));
        }

        return $a;
    }

    private function glog($n) {
        if ($n < 1) throw new \Exception("glog($n)");
        return QRMath::glog($n);
    }

    private function gexp($n) {
        while ($n < 0) $n += 255;
        while ($n >= 256) $n -= 255;
        return QRMath::gexp($n);
    }

    private function mapData($data, $maskPattern) {
        $inc = -1;
        $row = $this->moduleCount - 1;
        $bitIndex = 7;
        $byteIndex = 0;

        for ($col = $this->moduleCount - 1; $col > 0; $col -= 2) {
            if ($col == 6) $col--;

            while (true) {
                for ($c = 0; $c < 2; $c++) {
                    if ($this->isFunction[$row][$col - $c]) continue;

                    $dark = false;

                    if ($byteIndex < count($data)) {
                        $dark = ((($data[$byteIndex] >> $bitIndex) & 1) == 1);
                    }

                    if ($this->getMask($maskPattern, $row, $col - $c)) {
                        $dark = !$dark;
                    }

                    $this->modules[$row][$col - $c] = $dark;
                    $bitIndex--;

                    if ($bitIndex == -1) {
                        $byteIndex++;
                        $bitIndex = 7;
                    }
                }

                $row += $inc;

                if ($row < 0 || $this->moduleCount <= $row) {
                    $row -= $inc;
                    $inc = -$inc;
                    break;
                }
            }
        }
    }

    private function getMask($maskPattern, $i, $j) {
        switch ($maskPattern) {
            case 0: return ($i + $j) % 2 == 0;
            case 1: return $i % 2 == 0;
            case 2: return $j % 3 == 0;
            case 3: return ($i + $j) % 3 == 0;
            case 4: return (floor($i / 2) + floor($j / 3)) % 2 == 0;
            case 5: return (($i * $j) % 2) + (($i * $j) % 3) == 0;
            case 6: return ((($i * $j) % 2) + (($i * $j) % 3)) % 2 == 0;
            case 7: return ((($i * $j) % 3) + (($i + $j) % 2)) % 2 == 0;
            default: throw new \Exception("bad maskPattern:" . $maskPattern);
        }
    }

    public function getModuleCount() {
        return $this->moduleCount;
    }

    public function isDark($row, $col) {
        return $this->modules[$row][$col];
    }

    public function renderImage($pixelSize = 8, $margin = 4) {
        $size = $this->moduleCount * $pixelSize + $margin * 2 * $pixelSize;
        $img = imagecreatetruecolor($size, $size);

        $white = imagecolorallocate($img, 255, 255, 255);
        $black = imagecolorallocate($img, 0, 0, 0);

        imagefill($img, 0, 0, $white);

        for ($row = 0; $row < $this->moduleCount; $row++) {
            for ($col = 0; $col < $this->moduleCount; $col++) {
                if ($this->isDark($row, $col)) {
                    imagefilledrectangle(
                        $img,
                        ($margin + $col) * $pixelSize,
                        ($margin + $row) * $pixelSize,
                        ($margin + $col + 1) * $pixelSize - 1,
                        ($margin + $row + 1) * $pixelSize - 1,
                        $black
                    );
                }
            }
        }

        return $img;
    }
}