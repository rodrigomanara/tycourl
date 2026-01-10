<?php

namespace Codediesel\Library\QRGenerator;

class QRMath {
    private static $EXP_TABLE = null;
    private static $LOG_TABLE = null;

    private static function initTables() {
        if (self::$EXP_TABLE !== null) return;

        self::$EXP_TABLE = array_fill(0, 256, 0);
        self::$LOG_TABLE = array_fill(0, 256, 0);

        for ($i = 0; $i < 8; $i++) {
            self::$EXP_TABLE[$i] = 1 << $i;
        }

        for ($i = 8; $i < 256; $i++) {
            self::$EXP_TABLE[$i] = self::$EXP_TABLE[$i - 4]
                ^ self::$EXP_TABLE[$i - 5]
                ^ self::$EXP_TABLE[$i - 6]
                ^ self::$EXP_TABLE[$i - 8];
        }

        for ($i = 0; $i < 255; $i++) {
            self::$LOG_TABLE[self::$EXP_TABLE[$i]] = $i;
        }
    }

    public static function glog($n) {
        self::initTables();
        if ($n < 1) throw new \Exception("glog($n)");
        return self::$LOG_TABLE[$n];
    }

    public static function gexp($n) {
        self::initTables();
        while ($n < 0) $n += 255;
        while ($n >= 256) $n -= 255;
        return self::$EXP_TABLE[$n];
    }
}