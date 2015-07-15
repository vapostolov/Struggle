<?php

    namespace Example\Models;

    class FileStorage implements Storage {

        private $filename = null;

        public function __construct($filename) {
            $this->filename = $filename;
        }

        public function create($data) {
            throw new \Exception("Create method not implemented.");
        }

        public function read($id) {
            $file = new \SplFileObject($this->filename, "r");
            if ($file->flock(LOCK_EX)) {
                $file->seek($id - 1);
                $data = $file->current();
                $file->flock(LOCK_UN);
            }

            return $this->mapStringDataToObject($data);
        }

        public function update($id, $data) {
            throw new \Exception("Update method not implemented.");
        }

        public function delete($id) {
            throw new \Exception("Delete method not implemented.");
        }
        
        private function mapStringDataToObject($data) {
            $data = str_getcsv($data);
            return $data;
        }

    }
?>