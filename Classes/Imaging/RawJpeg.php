<?php
namespace Bash\ExifOrientationHelper\Imaging
{
    class RawJpeg
    {
        /**
         * @var string
         */
        private $filename;

        /**
         * @var resource
         */
        private $image;

        /**
         * @var array
         */
        private $data;

        public function __construct($filename)
        {
            $this->filename = $filename;
        }

        public function getExifData()
        {
            if ($this->data === null) {
                $this->data = @read_exif_data($this->filename);
            }

            return $this->data;
        }

        public function getOrientation()
        {
            $data = $this->getExifData();

            if ($data && isset($data['Orientation'])) {
                return $data['Orientation'];
            }

            return null;
        }

        public function hasOrientation()
        {
            return $this->getOrientation() !== null;
        }

        public function rotate($angle)
        {
            $this->image = imagerotate($this->getImage(), $angle, null);
        }

        public function flip($mode)
        {
            imageflip($this->getImage(), $mode);
        }

        public function write($filename)
        {
            touch($filename);
            imagejpeg($this->image, $filename, 100);
        }

        private function getImage()
        {
            if ($this->image === null) {
                $this->image = imagecreatefromjpeg($this->filename);
            }

            return $this->image;
        }
    }
}
