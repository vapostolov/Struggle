<?php

    namespace Example\Models;

    class UserRepository {
        private $storage;

        public function __construct($storage) {
            $this->storage = $storage;
        }

        public function getUserById($id) {
            return $this->storage->read($id);
        }

        public function createUser($data) {

        }

        public function deleteUser($id) {

        }

    }
?>