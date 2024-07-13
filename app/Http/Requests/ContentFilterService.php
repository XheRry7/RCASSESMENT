<?php

namespace App\Services;

class ContentFilterService
{
    protected $badWords = ['badword1', 'badword2']; // Add more words as needed

    public function filter(string $content): bool
    {
        foreach ($this->badWords as $word) {
            if (stripos($content, $word) !== false) {
                return false;
            }
        }
        return true;
    }
}
