<?php

namespace AsciiArt;

class AsciiArt
{
    public function generateFrom($filePath)
    {
        $imagick = new \Imagick;

        $imageBlob = file_get_contents($filePath);

        if (true === $imagick->readImageBlob($imageBlob)) {
            $width = 50;
            $scale = ($width / $imagick->getImageWidth());
            $height = (int) (($imagick->getImageWidth() * $scale) / 2);

            $imagick->resizeImage($width, $height, \Imagick::FILTER_LANCZOS, 1.0);

            $numberOfColors = count($this->getImageCharacters());
            if (true === $imagick->quantizeImage($numberOfColors, \Imagick::COLORSPACE_GRAY, 0, true, false)) {
                $imagick->normalizeImage();
                $quantumRange = $imagick->getQuantumRange();
                $quantumCalculation =  255 / \Imagick::PIXEL_QUANTUM;
                $border = "+" . str_repeat('-', $width) . "+" . PHP_EOL;

                $output = $border;

                $pixelIterator = $imagick->getPixelIterator();

                foreach ($pixelIterator as $row => $pixels) {
                    $output .= '|';
                    foreach ($pixels as $column => $pixel) {
                        $foo = $pixel->getColor();
                        $foo = $foo['g'];
                        $imageChars = $this->getImageCharacters();
                        $bar = $foo / $quantumCalculation;
                        $char = $imageChars[$bar];
                        $output .= $char;
                    }
                    $output .= "|" . PHP_EOL;
                }
                $output .= $border;
            }
        }

        echo $output;

    }

    public function getImageCharacters()
    {
        return array(
            ' ',
            '.',
            '~',
            ':',
            '+',
            '=',
            'o',
            '*',
            'x',
            '^',
            '%',
            '#',
            '@',
            '$',
            'M',
            'W'
        );
    }
}
