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

        public function deleteUserById($id) {
            return $this->storage->delete($id);
        }

        public function updateUserById($id, $data) {
            return $this->storage->update($id, $this->mapJsonDataToUser($data));
        }

        private function mapJsonDataToUser($data) {
            $data = json_decode($data);
            $user = new User();
            $user->name = $data->name;
            $user->phone = $data->phone;
            $user->address = $data->address;
            return $user;
        }

    }
?>