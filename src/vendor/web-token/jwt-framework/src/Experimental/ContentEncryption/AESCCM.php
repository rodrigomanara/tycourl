<?php

declare(strict_types=1);

namespace Jose\Experimental\ContentEncryption;

use Jose\Component\Encryption\Algorithm\ContentEncryptionAlgorithm;
use Override;
use RuntimeException;
use const OPENSSL_RAW_DATA;

abstract readonly class AESCCM implements ContentEncryptionAlgorithm
{
    #[Override]
    public function allowedKeyTypes(): array
    {
        return []; //Irrelevant
    }

    #[Override]
    public function encryptContent(
        string $data,
        string $cek,
        string $iv,
        ?string $aad,
        string $encoded_protected_header,
        ?string &$tag = null
    ): string {
        $calculated_aad = $encoded_protected_header;
        if ($aad !== null) {
            $calculated_aad .= '.' . $aad;
        }
        $tag = '';
        $result = openssl_encrypt(
            $data,
            $this->getMode(),
            $cek,
            OPENSSL_RAW_DATA,
            $iv,
            $tag,
            $calculated_aad,
            $this->getTagLength()
        );
        if ($result === false) {
            throw new RuntimeException('Unable to encrypt the content');
        }

        return $result;
    }

    #[Override]
    public function decryptContent(
        string $data,
        string $cek,
        string $iv,
        ?string $aad,
        string $encoded_protected_header,
        string $tag
    ): string {
        $calculated_aad = $encoded_protected_header;
        if ($aad !== null) {
            $calculated_aad .= '.' . $aad;
        }

        $result = openssl_decrypt($data, $this->getMode(), $cek, OPENSSL_RAW_DATA, $iv, $tag, $calculated_aad);
        if ($result === false) {
            throw new RuntimeException('Unable to decrypt the content');
        }

        return $result;
    }

    abstract protected function getMode(): string;

    abstract protected function getTagLength(): int;
}
