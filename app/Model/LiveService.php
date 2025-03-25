<?php

namespace Kozak\Tomas\App\Model;

final class LiveService
{

    public function __construct(
        private string $liveFile,
        private string $liveToken,
    )
    {
    }

    public function isTokenValid(string $token): bool
    {
        return $token === $this->liveToken;
    }

    public function setLive(bool $live): void
    {
        if ($live) {
            \touch($this->liveFile);
        } else {
            @\unlink($this->liveFile);
        }
    }

    public function isLive(): bool
    {
        return \file_exists($this->liveFile);
    }

}